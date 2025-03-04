<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

class ExportBagistoCsv extends Command
{
    protected $signature = 'bagisto:export-csv 
                            {prodotti? : Numero di prodotti da importare (opzionale)}
                            {--chunk=1000 : Numero di prodotti da elaborare per batch} 
                            {--skip=0 : Numero di prodotti da saltare} 
                            {--limit=0 : Limite totale di prodotti (0 per nessun limite)} 
                            {--image-batch=10 : Dimensione del batch per il download delle immagini}
                            {--skip-images : Salta il download delle immagini}
                            {--image-limit=1 : Limita il numero di immagini per prodotto}
                            {--debug : Mostra informazioni di debug}
                            {--codes= : Elenco di codici MINSAN separati da virgola}';
                            
    protected $description = 'Estrae prodotti e scarica immagini per Bagisto';
    
    protected $client;
    protected $downloadedImages = [];
    protected $debug = false;
    
    // File CSV temporanei per ogni chunk
    protected $tempFiles = [];
    protected $totalProducts = 0;
    protected $productsWithDiscount = 0;
    protected $productsWithImages = 0;
    protected $outputFile;
    
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            'timeout' => 10,
            'connect_timeout' => 5,
            'verify' => false,
        ]);
    }

    public function handle()
    {
        $this->debug = $this->option('debug');
        $this->info('Inizio esportazione dati prodotti...');
        $startTime = microtime(true);
        
        $chunkSize = (int) $this->option('chunk');
        $skip = (int) $this->option('skip');
        
        // Priorità: 1) argomento posizionale, 2) opzione --limit
        $numProdotti = $this->argument('prodotti');
        $limit = 0;
        
        if (!empty($numProdotti) && is_numeric($numProdotti)) {
            $limit = (int) $numProdotti;
            $this->info("Limite prodotti da importare (da argomento): {$limit}");
        } else {
            $limit = (int) $this->option('limit');
            if ($limit > 0) {
                $this->info("Limite prodotti da importare (da opzione --limit): {$limit}");
            }
        }
        
        $imageBatch = (int) $this->option('image-batch');
        $skipImages = $this->option('skip-images');
        $specificCodes = $this->option('codes');
        
        // Crea il file di output finale
        $this->outputFile = storage_path('app/public/bagisto_import.csv');
        
        // Inizializza il file principale con le intestazioni
        $headers = $this->getHeaders();
        $this->writeHeaders($this->outputFile, $headers);
        
        // Gestione codici MINSAN specifici
        if (!empty($specificCodes)) {
            $minsanCodes = explode(',', $specificCodes);
            $minsanCodes = array_map('trim', $minsanCodes);
            $this->info("Elaborazione di " . count($minsanCodes) . " codici MINSAN specifici");
            
            // Verifica che i codici esistano nel database
            $validCodes = $this->verifyMinsanCodes($minsanCodes);
            if (count($validCodes) == 0) {
                $this->error("Nessun codice MINSAN valido trovato");
                return 1;
            }
            
            // Elabora direttamente i prodotti specificati
            $products = $this->processProductBatch($validCodes);
            
            // Scarica le immagini (se non disattivato)
            if (!$skipImages) {
                $imageQueue = $this->prepareImagesForDownload($products);
                $this->downloadImagesInParallel($imageQueue, $imageBatch);
            }
            
            // Scrivi i dati CSV
            $this->writeCsvBatch($this->outputFile, $products);
            
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            
            $this->info("\nOperazione completata in {$executionTime} secondi");
            $this->info("CSV generato: {$this->outputFile}");
            $this->info("Totale prodotti elaborati: {$this->totalProducts}");
            $this->info("Prodotti con prezzo speciale: {$this->productsWithDiscount} (" . 
                ($this->totalProducts > 0 ? round(($this->productsWithDiscount / $this->totalProducts) * 100, 1) : 0) . "%)");
            $this->info("Prodotti con immagini: {$this->productsWithImages} (" . 
                ($this->totalProducts > 0 ? round(($this->productsWithImages / $this->totalProducts) * 100, 1) : 0) . "%)");
            
            return 0;
        }
        
        // Altrimenti procedi con l'esportazione normale
        // Ottieni il conteggio totale di MINSAN_CODE distinti
        $this->info('Conteggio prodotti totali...');
        $totalMinsanCount = DB::table('combined_pharma_data_table')
            ->where('price_original', '>', 0)
            ->distinct('MINSAN_CODE')
            ->count('MINSAN_CODE');
            
        $this->info("Totale prodotti disponibili: {$totalMinsanCount}");
        
        // Se specificato un limite, usalo, altrimenti usa il totale
        $totalToProcess = ($limit > 0) ? min($limit, $totalMinsanCount - $skip) : $totalMinsanCount - $skip;
        $this->info("Saranno elaborati {$totalToProcess} prodotti, in batch da {$chunkSize}");
        
        // Assicurati che la directory per le immagini esista
        if (!$skipImages) {
            $imagesDir = storage_path('app/public/import/images');
            if (!is_dir($imagesDir)) {
                mkdir($imagesDir, 0755, true);
                $this->info("Creata directory per le immagini: {$imagesDir}");
            }
        }
        
        $offset = $skip;
        $processedCount = 0;
        $batchNumber = 1;
        
        // Elabora i prodotti in batch
        while ($processedCount < $totalToProcess) {
            $currentBatchSize = min($chunkSize, $totalToProcess - $processedCount);
            $this->info("Elaborazione batch #{$batchNumber}: {$currentBatchSize} prodotti (offset: {$offset})");
            
            // 1. Ottieni un batch di codici MINSAN
            $minsanCodes = DB::table('combined_pharma_data_table')
                ->where('price_original', '>', 0)
                ->select('MINSAN_CODE')
                ->distinct()
                ->offset($offset)
                ->limit($currentBatchSize)
                ->pluck('MINSAN_CODE');
                
            $actualBatchSize = count($minsanCodes);
            $this->info("Trovati {$actualBatchSize} codici MINSAN distinti");
            
            if ($actualBatchSize == 0) {
                $this->info("Nessun altro prodotto da elaborare");
                break;
            }
            
            // 2. Elabora i prodotti di questo batch
            $products = $this->processProductBatch($minsanCodes);
            
            // 3. Scarica le immagini (se non disattivato)
            if (!$skipImages) {
                $imageQueue = $this->prepareImagesForDownload($products);
                $this->downloadImagesInParallel($imageQueue, $imageBatch);
            }
            
            // 4. Scrivi i dati CSV per questo batch
            $tempFile = storage_path("app/public/bagisto_import_batch_{$batchNumber}.csv");
            $this->tempFiles[] = $tempFile;
            $this->writeCsvBatch($tempFile, $products);
            
            // 5. Aggiorna contatori e passa al batch successivo
            $processedCount += $actualBatchSize;
            $offset += $actualBatchSize;
            $batchNumber++;
            
            // Mostra lo stato di avanzamento
            $percentComplete = round(($processedCount / $totalToProcess) * 100, 1);
            $this->info("Completato: {$processedCount}/{$totalToProcess} prodotti ({$percentComplete}%)");
            
            // Pulizia della memoria
            unset($products);
            unset($minsanCodes);
            gc_collect_cycles();
        }
        
        // 6. Combina tutti i file temporanei nel file finale
        $this->mergeTempFiles();
        
        // 7. Verifica il file CSV se richiesto
        if ($this->debug) {
            $this->verifyCSV($this->outputFile);
        }
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        
        $this->info("\nOperazione completata in {$executionTime} secondi");
        $this->info("CSV generato: {$this->outputFile}");
        $this->info("Totale prodotti elaborati: {$this->totalProducts}");
        $this->info("Prodotti con prezzo speciale: {$this->productsWithDiscount} (" . 
            round(($this->productsWithDiscount / $this->totalProducts) * 100, 1) . "%)");
        $this->info("Prodotti con immagini: {$this->productsWithImages} (" . 
            round(($this->productsWithImages / $this->totalProducts) * 100, 1) . "%)");
        
        return 0;
    }
    
    /**
     * Verifica che i codici MINSAN specificati esistano nel database
     */
    protected function verifyMinsanCodes(array $codes)
    {
        $validCodes = DB::table('combined_pharma_data_table')
            ->whereIn('MINSAN_CODE', $codes)
            ->distinct('MINSAN_CODE')
            ->pluck('MINSAN_CODE')
            ->toArray();
        
        $invalidCodes = array_diff($codes, $validCodes);
        
        if (!empty($invalidCodes)) {
            $this->warn("I seguenti codici MINSAN non sono stati trovati nel database:");
            foreach ($invalidCodes as $code) {
                $this->line(" - {$code}");
            }
        }
        
        return $validCodes;
    }
    
    /**
     * Elabora un batch di prodotti per codici MINSAN
     */
    protected function processProductBatch($minsanCodes)
    {
        $products = collect();
        $bar = $this->output->createProgressBar(count($minsanCodes));
        $bar->start();
        
        // Ottimizzazione: precaricare tutti i prezzi dei fornitori in una sola query
        $allFornitoriPrezzi = DB::table('prezzi_fornitori')
            ->whereIn('minsan', $minsanCodes)
            ->where('prezzo_no_iva', '>', 0)
            ->select('minsan', 'prezzo_no_iva')
            ->get()
            ->groupBy('minsan');
        
        foreach ($minsanCodes as $minsan) {
            $minsan = trim($minsan);
            
            // Query ottimizzata: seleziona solo i campi necessari
            // NOTA: Rimosso 'description' perché non esiste nella tabella
            $records = DB::table('combined_pharma_data_table')
                ->where('MINSAN_CODE', $minsan)
                ->select('title', 'category', 'brand', 'image_url', 'price_original', 'price_discounted')
                ->get();
                
            if ($records->isEmpty()) {
                $bar->advance();
                continue;
            }
            
            // Troviamo il prezzo originale massimo (escludendo NULL e 0)
            $maxOriginalPrice = $records->where('price_original', '>', 0)->max('price_original');
            
            // Troviamo il prezzo scontato minimo (escludendo NULL e 0)
            $validDiscountedPrices = $records
                ->filter(function($record) {
                    return !is_null($record->price_discounted) && 
                           $record->price_discounted > 0 &&
                           $record->price_discounted < $record->price_original;
                })
                ->pluck('price_discounted');
                
            $minDiscountedPrice = $validDiscountedPrices->isEmpty() ? null : $validDiscountedPrices->min();
            
            // Ottimizzazione: usa i prezzi fornitori precalcolati invece di una query per ogni prodotto
            $fornitoriPrezzi = $allFornitoriPrezzi->get($minsan) ?? collect();
            
            $firstRecord = $records->first();
            
            // Creiamo un oggetto prodotto
            $product = (object)[
                'MINSAN_CODE' => $minsan,
                'title' => $firstRecord->title,
                'category' => $firstRecord->category,
                'brand' => $firstRecord->brand,
                'image_url' => $firstRecord->image_url,
                'price_original' => $maxOriginalPrice,
                'price_discounted' => $minDiscountedPrice,
                'final_price' => $maxOriginalPrice,
                'final_discounted_price' => $minDiscountedPrice
            ];
            
            // Aggiungiamo i prezzi dei fornitori se disponibili
            if ($fornitoriPrezzi->count() > 0) {
                $prezziFornitori = $fornitoriPrezzi
                    ->map(function($p) {
                        return (float) $p->prezzo_no_iva * 1.22;
                    })
                    ->filter(function($price) {
                        return $price > 0;
                    });
                
                if ($prezziFornitori->count() > 0) {
                    // Considera anche i prezzi dei fornitori nel calcolo
                    $allOriginalPrices = collect([$maxOriginalPrice]);
                    $allOriginalPrices = $allOriginalPrices->merge($prezziFornitori);
                    $product->final_price = $allOriginalPrices->max();
                    
                    // Per il prezzo scontato, prendi il minimo tra tutti i prezzi disponibili
                    $allPrices = $prezziFornitori->toArray();
                    if (!is_null($minDiscountedPrice)) {
                        $allPrices[] = $minDiscountedPrice;
                    }
                    
                    if (!empty($allPrices)) {
                        $product->final_discounted_price = min($allPrices);
                    }
                }
            }
            
            $products->push($product);
            $bar->advance();
        }
        
        $bar->finish();
        $this->line('');
        
        return $products;
    }
    
    /**
     * Prepara l'elenco delle immagini da scaricare
     */
    protected function prepareImagesForDownload($products)
    {
        $imageQueue = [];
        $imageLimit = (int) $this->option('image-limit');
        
        // Assicurati che questa directory esista
        $imagesDir = storage_path('app/public/import/images');
        if (!is_dir($imagesDir)) {
            mkdir($imagesDir, 0755, true);
        }
        
        foreach ($products as $product) {
            if (!empty($product->image_url)) {
                $sku = $product->MINSAN_CODE;
                $cleanUrl = strtok($product->image_url, '?');
                $ext = pathinfo($cleanUrl, PATHINFO_EXTENSION) ?: 'jpg';
                
                // Limite per immagine per prodotto (di solito ne basta 1)
                for ($i = 0; $i < $imageLimit; $i++) {
                    $filename = "{$sku}_{$i}.{$ext}";
                    $fullPath = storage_path('app/public/import/images/' . $filename);
                    
                    // Verifica se l'immagine esiste già
                    if (!file_exists($fullPath) && !isset($this->downloadedImages[$sku])) {
                        $imageQueue[] = [
                            'sku' => $sku,
                            'url' => $product->image_url,
                            'path' => $fullPath,
                            'filename' => $filename
                        ];
                        break; // Scarica solo una volta per prodotto
                    } else {
                        $this->downloadedImages[$sku] = $filename;
                    }
                }
            }
        }
        
        return $imageQueue;
    }
    
    /**
     * Download parallelo delle immagini
     */
    protected function downloadImagesInParallel($imageQueue, $batchSize)
    {
        if (empty($imageQueue)) {
            $this->info('Nessuna nuova immagine da scaricare');
            return;
        }
        
        $this->info("Download di " . count($imageQueue) . " immagini in parallelo...");
        $bar = $this->output->createProgressBar(count($imageQueue));
        $bar->start();
        
        // Assicura che la directory esista
        $dir = storage_path('app/public/import/images');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Divide le immagini in batch
        $chunks = array_chunk($imageQueue, $batchSize);
        
        foreach ($chunks as $chunk) {
            $promises = [];
            
            foreach ($chunk as $image) {
                $sku = $image['sku'];
                $url = $image['url'];
                $path = $image['path'];
                
                $promises[$sku] = $this->client->getAsync($url, [
                    'sink' => $path,
                    'timeout' => 3,
                    'connect_timeout' => 2,
                ])->then(
                    function (Response $response) use ($sku, $image) {
                        $this->downloadedImages[$sku] = $image['filename'];
                        return true;
                    },
                    function (RequestException $e) use ($sku) {
                        // Fallimento silenzioso, non blocchiamo per le immagini
                        return false;
                    }
                );
                
                $bar->advance();
            }
            
            // Attendi che tutte le richieste nel batch siano completate
            Promise\Utils::settle($promises)->wait();
        }
        
        $bar->finish();
        $this->line('');
    }
    
    /**
     * Scrive un batch di prodotti in un file CSV
     */
    protected function writeCsvBatch($filePath, $products)
    {
        $file = fopen($filePath, 'w');
        
        if (!$file) {
            $this->error("Impossibile aprire il file {$filePath} per la scrittura");
            return;
        }
        
        $this->info("Scrittura di " . count($products) . " prodotti nel file " . basename($filePath));
        
        // Se è un file temporaneo, scrivi anche le intestazioni
        if (in_array($filePath, $this->tempFiles)) {
            $headers = $this->getHeaders();
            fputcsv($file, $headers);
        }
        
        // Contatore per questo batch
        $batchProductCount = 0;
        $batchDiscountCount = 0;
        $batchImagesCount = 0;
        
        foreach ($products as $product) {
            $batchProductCount++;
            $this->totalProducts++;
            
            $sku = $product->MINSAN_CODE;
            $name = $product->title;
            $urlKey = Str::slug($name);
    
            // Prezzo originale (garantito che sia un float)
            $price = (float) $product->final_price;
            if ($price <= 0) continue; // Salta prodotti senza prezzo
            $price = round($price, 2);
            
            // Prezzo scontato
            $specialPrice = '';
            if (!is_null($product->final_discounted_price)) {
                $rawDiscountedPrice = (float) $product->final_discounted_price;
                
                // Usa il prezzo scontato solo se è effettivamente minore del prezzo originale
                if ($rawDiscountedPrice > 0 && $rawDiscountedPrice < $price) {
                    $specialPrice = round($rawDiscountedPrice, 2);
                    $this->productsWithDiscount++;
                    $batchDiscountCount++;
                }
            }
            
            // Usa l'immagine scaricata se disponibile - IMPORTANTE: formato richiesto da Bagisto
            $imagePath = '';
            if (isset($this->downloadedImages[$sku])) {
                // Per Bagisto bisogna usare il path completo per l'importazione
                $imagePath = "import/images/" . $this->downloadedImages[$sku]; 
                $this->productsWithImages++;
                $batchImagesCount++;
            }
            
            // Assicurati che le descrizioni non siano vuote
            $description = $product->title; // Usiamo title come descrizione 
            $shortDescription = !empty($product->brand) ? $product->brand : $product->title;
            
            $rowData = [
                $sku,                   // sku
                '',                     // parent_sku
                'it',                   // locale
                'default',              // attribute_family_code
                'simple',               // type
                $product->category,     // categories
                $product->image_url,    // images
                $name,                  // name
                $description,           // description - campo obbligatorio
                $shortDescription,      // short_description - campo obbligatorio
                1,                      // status
                1,                      // visible_individually
                '',                     // new
                '',                     // featured
                1,                      // guest_checkout
                '',                     // length
                '',                     // width
                '',                     // height
                '0.5',                  // weight
                '',                     // tax_category_name
                $price,                 // price
                '',                     // cost
                $specialPrice,          // special_price
                '',                     // special_price_from
                '',                     // special_price_to
                '',                     // customer_group_prices
                $urlKey,                // url_key
                $name,                  // meta_title
                'meta1, meta2',         // meta_keywords
                'meta description',     // meta_description
                1,                      // manage_stock
                'default=100',          // inventories
                '',                     // related_skus
                '',                     // cross_sell_skus
                '',                     // up_sell_skus
                '',                     // configurable_variants
                '',                     // bundle_options
                '',                     // associated_skus
            ];
            
            fputcsv($file, $rowData);
        }
        
        fclose($file);
        $this->info("Batch completato: {$batchProductCount} prodotti, {$batchDiscountCount} con prezzo speciale, {$batchImagesCount} con immagini");
    }
    
    /**
     * Combina tutti i file temporanei nel file finale
     */
    protected function mergeTempFiles()
    {
        $this->info("Combinazione dei file CSV temporanei...");
        
        $outputFile = fopen($this->outputFile, 'a'); // Apri in modalità append (intestazioni già scritte)
        
        foreach ($this->tempFiles as $index => $tempFile) {
            $this->info("Unione file temporaneo " . ($index + 1) . "/" . count($this->tempFiles));
            
            if (file_exists($tempFile)) {
                $tempFileHandle = fopen($tempFile, 'r');
                
                // Salta l'intestazione nel file temporaneo
                fgetcsv($tempFileHandle);
                
                // Leggi e scrivi il file temporaneo riga per riga
                while (($row = fgetcsv($tempFileHandle)) !== false) {
                    fputcsv($outputFile, $row);
                }
                
                fclose($tempFileHandle);
                
                // Elimina il file temporaneo
                unlink($tempFile);
            }
        }
        
        fclose($outputFile);
        $this->info("Tutti i file temporanei sono stati combinati nel file finale");
    }
    
    /**
     * Scrive le intestazioni nel file CSV
     */
    protected function writeHeaders($filePath, $headers)
    {
        $file = fopen($filePath, 'w');
        
        // Non usiamo BOM UTF-8 perché causa problemi con Bagisto
        // Uso semplice fputcsv senza delimitatori espliciti
        fputcsv($file, $headers);
        
        fclose($file);
    }
    
    /**
     * Ottiene le intestazioni del CSV
     */
    protected function getHeaders()
    {
        return [
            'sku', 'parent_sku', 'locale', 'attribute_family_code', 'type',
            'categories', 'images', 'name', 'description', 'short_description',
            'status', 'visible_individually', 'new', 'featured', 'guest_checkout',
            'length', 'width', 'height', 'weight', 'tax_category_name',
            'price', 'cost', 'special_price', 'special_price_from', 'special_price_to',
            'customer_group_prices', 'url_key', 'meta_title', 'meta_keywords',
            'meta_description', 'manage_stock', 'inventories', 'related_skus',
            'cross_sell_skus', 'up_sell_skus', 'configurable_variants',
            'bundle_options', 'associated_skus'
        ];
    }
    
    /**
     * Verifica il file CSV generato
     */
    protected function verifyCSV($filePath)
    {
        $this->info("Verifica del file CSV...");
        
        // Leggi le prime righe del file
        $file = fopen($filePath, 'r');
        if (!$file) {
            $this->error("Impossibile aprire il file per la verifica");
            return;
        }
        
        // Leggi l'intestazione
        $headers = fgetcsv($file);
        if (!$headers) {
            $this->error("Errore: Intestazioni non trovate nel CSV");
            return;
        }
        
        $this->info("Intestazioni trovate: " . count($headers));
        
        // Leggi alcune righe di dati
        $rows = [];
        $rowCount = 0;
        while (($row = fgetcsv($file)) !== false && $rowCount < 3) {
            $rows[] = $row;
            $rowCount++;
        }
        
        fclose($file);
        
        // Verifica che le righe abbiano lo stesso numero di colonne delle intestazioni
        foreach ($rows as $index => $row) {
            if (count($row) !== count($headers)) {
                $this->error("Errore: La riga " . ($index + 1) . " ha " . count($row) . " colonne, ma le intestazioni ne hanno " . count($headers));
            } else {
                $this->info("Riga " . ($index + 1) . ": " . count($row) . " colonne (OK)");
            }
        }
        
        // Verifica che i campi obbligatori non siano vuoti
        $requiredFields = ['sku', 'name', 'description', 'short_description', 'price'];
        foreach ($rows as $index => $row) {
            foreach ($requiredFields as $field) {
                $fieldIndex = array_search($field, $headers);
                if ($fieldIndex !== false && empty($row[$fieldIndex])) {
                    $this->warn("Attenzione: Il campo obbligatorio '$field' è vuoto nella riga " . ($index + 1));
                }
            }
        }
        
        // Visualizza i valori delle intestazioni e delle prime righe se in debug mode
        if ($this->debug) {
            $this->info("Intestazioni:");
            $this->line(implode(', ', $headers));
            
            foreach ($rows as $index => $row) {
                $this->info("Riga " . ($index + 1) . ":");
                foreach ($headers as $colIndex => $header) {
                    if (isset($row[$colIndex])) {
                        $this->line($header . ": " . $row[$colIndex]);
                    } else {
                        $this->line($header . ": NULL");
                    }
                }
            }
        }
    }
}