<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PrezziFC;

class ExportBagistoCsv extends Command
{
    protected $signature = 'bagisto:export-csv';
    protected $description = 'Estrae prodotti e scarica immagini per Bagisto';

    public function handle()
    {
        $this->info('Inizio esportazione...');

        $this->info('Inizio esportazione...');

        $products = DB::table('combined_pharma_data_table')
            ->leftJoin('prezzi_fornitori', 
                DB::raw('combined_pharma_data_table.MINSAN_CODE COLLATE utf8mb4_general_ci'), 
                '=', 
                DB::raw('prezzi_fornitori.minsan COLLATE utf8mb4_general_ci')
            )
            ->select(
                'combined_pharma_data_table.*',
                DB::raw('COALESCE(prezzi_fornitori.prezzo_no_iva * 1.22, combined_pharma_data_table.price_original) as final_price')
            )
            ->take(50)
            ->get();

        $total = $products->count();
        $this->info("Trovati {$total} prodotti");

        $filePath = storage_path('app/public/bagisto_import.csv');
        $file = fopen($filePath, 'w');

        fputcsv($file, [
            'sku',
            'parent_sku',
            'locale',
            'attribute_family_code',
            'type',
            'categories',
            'images',
            'name',
            'description',
            'short_description',
            'status',
            'visible_individually',
            'new',
            'featured',
            'guest_checkout',
            'length',
            'width',
            'height',
            'weight',
            'tax_category_name',
            'price',
            'cost',
            'special_price',
            'special_price_from',
            'special_price_to',
            'customer_group_prices',
            'url_key',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'manage_stock',
            'inventories',
            'related_skus',
            'cross_sell_skus',
            'up_sell_skus',
            'configurable_variants',
            'bundle_options',
            'associated_skus',
        ]);

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($products as $product) {
            try {
                $sku = $product->MINSAN_CODE;
                $name = $product->title;
                $urlKey = Str::slug($name);
                $price = $product->final_price; // Usa il nuovo prezzo calcolato
                $specialPrice = ($product->price_discounted && $product->price_discounted < $price) 
                    ? $product->price_discounted 
                    : '';

                $imagePath = '';
                if ($product->image_url) {
                    $imagePath = $this->handleImage($product->image_url, $sku);
                }

                fputcsv($file, [
                    $sku,                   // sku
                    '',                     // parent_sku
                    'it',                   // locale
                    'default',              // attribute_family_code
                    'simple',               // type
                    $product->category,     // categories
                    $product->image_url,   // images
                    $name,                  // name
                    $product->title,        // description
                    $product->brand ?? '',  // short_description
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
                ]);

                $bar->advance();

            } catch (\Exception $e) {
                $this->error("Errore con SKU {$sku}: " . $e->getMessage());
                continue;
            }
        }

        $bar->finish();
        fclose($file);
        
        $this->info("\nCSV generato: {$filePath}");
    }

    protected function handleImage($url, $sku) 
        {
            try {
                // Rimuovi parametri URL
                $cleanUrl = strtok($url, '?');
                
                $ext = pathinfo($cleanUrl, PATHINFO_EXTENSION) ?: 'jpg';
                $filename = "{$sku}_0.{$ext}"; // Aggiunto _0 prima dell'estensione
                
                // Path relativo come richiesto da Bagisto
                $relativePath = "import/images/{$filename}";
                $fullPath = public_path('storage/' . $relativePath);
        
                // Crea directory se non esiste
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }
        
                if (!file_exists($fullPath)) {
                    $content = @file_get_contents($url);
                    if ($content) {
                        file_put_contents($fullPath, $content);
                    }
                }
        
                // Ritorna solo il nome del file per il CSV
                return $filename;
        
            } catch (\Exception $e) {
                $this->warn("Errore scaricamento immagine per SKU {$sku}");
                return '';
            }
        }
}