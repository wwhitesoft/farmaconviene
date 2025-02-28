<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;

class ImportProductsFromDatabase extends Command
{
    protected $signature = 'products:import-db';
    protected $description = 'Importa prodotti dal database pharma';

    protected $categoryRepository;
    protected $productRepository;
    protected $attributeFamilyRepository;
    protected $channelRepository;
    protected $attributeRepository;
    protected $attributeOptionRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        AttributeFamilyRepository $attributeFamilyRepository,
        ChannelRepository $channelRepository,
        AttributeRepository $attributeRepository,
        AttributeOptionRepository $attributeOptionRepository
    ) {
        parent::__construct();
        
        $this->categoryRepository      = $categoryRepository;
        $this->productRepository       = $productRepository;
        $this->attributeFamilyRepository = $attributeFamilyRepository;
        $this->channelRepository       = $channelRepository;
        $this->attributeRepository     = $attributeRepository;
        $this->attributeOptionRepository = $attributeOptionRepository;
    }

    public function handle()
    {
        $this->info('Avvio importazione...');

        $channel = $this->channelRepository->first();
        if (!$channel) {
            $this->error('Nessun canale trovato.');
            return;
        }

        $attributeFamily = $this->attributeFamilyRepository->first();
        if (!$attributeFamily) {
            $this->error('Nessuna attributeFamily trovata.');
            return;
        }

        // Esempio: adatta campi ai nomi reali della tua tabella
        $products = DB::table('combined_pharma_data_table')->get();
        $total    = $products->count();
        $this->info("Prodotti trovati: {$total}");

        if ($total === 0) {
            return;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $successCount = 0;
        $errorCount   = 0;

        foreach ($products as $dbProduct) {
            try {
                // Crea o recupera categoria
                $category = $this->createOrGetCategory($dbProduct->category);

                // Scarica immagine
                $imagePath = $this->downloadAndSaveImage($dbProduct->image_url, $dbProduct->product_sku);

                // Crea o recupera brand
                $brandId = $this->createOrGetBrand($dbProduct->brand);

                // Crea prodotto
                $createdProduct = $this->productRepository->create([
                    'type'                => 'simple',
                    'attribute_family_id' => $attributeFamily->id,
                    'sku'                 => $dbProduct->product_sku,
                    'name'                => $dbProduct->product_name,
                    'url_key'             => Str::slug($dbProduct->product_name) . '-' . $dbProduct->product_sku,
                    'price'               => (float) $dbProduct->price,
                    'status'              => 1,
                    'visible_individually'=> 1,
                    'description'         => $dbProduct->short_description ?? $dbProduct->product_name,
                    'short_description'   => $dbProduct->brand ?? '',
                    'meta_title'          => $dbProduct->product_name,
                    'categories'          => [$category->id],

                    // Imposta brand come opzione attributo
                    'brand'               => $brandId,

                    'images' => [
                        [
                            'path' => $imagePath,
                        ]
                    ],

                    'inventories' => [
                        [
                            'inventory_source_id' => 1,
                            'qty'                => $dbProduct->qty ?? 100
                        ]
                    ]
                ]);

                $successCount++;
                $bar->advance();
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("\nErrore con {$dbProduct->product_name}: {$e->getMessage()}");
                Log::error($e->getMessage());
            }
        }

        $bar->finish();

        $this->info("\nImportazione completata!");
        $this->info("Successi: {$successCount}");
        $this->info("Errori: {$errorCount}");

        // Popola product_flat
        $this->call('indexer:reindex');
    }

    protected function createOrGetCategory($name)
    {
        if (!$name) {
            $name = 'Generico';
        }

        $slug = Str::slug($name);
        $category = $this->categoryRepository->findOneByField('slug', $slug);

        if (!$category) {
            $category = $this->categoryRepository->create([
                'name'   => $name,
                'slug'   => $slug,
                'status' => 1,
            ]);
        }

        return $category;
    }

    protected function downloadAndSaveImage($url, $sku)
    {
        if (!$url) {
            return null;
        }

        $extension = pathinfo($url, PATHINFO_EXTENSION) ?: 'jpg';
        $filename  = "{$sku}.{$extension}";
        $path      = "product-images/{$filename}";

        if (!Storage::exists($path)) {
            $contents = @file_get_contents($url);

            if ($contents) {
                Storage::put($path, $contents);
            } else {
                Log::warning("Impossibile scaricare l'immagine da: {$url}");
                return null;
            }
        }

        return $path;
    }

    // Crea o recupera opzione brand in base all’attributo “brand”
    protected function createOrGetBrand($brandName)
    {
        if (!$brandName) {
            $brandName = 'Senza Brand';
        }

        $brandAttribute = $this->attributeRepository->findOneByField('code', 'brand');
        if (!$brandAttribute) {
            throw new \Exception("Attributo 'brand' non trovato in Bagisto");
        }

        // Cerca se l’opzione brandName esiste già
        $brandOption = $this->attributeOptionRepository->findOneWhere([
            'attribute_id' => $brandAttribute->id,
            'admin_name'   => $brandName,
        ]);

        // Se non esiste, creala
        if (!$brandOption) {
            $brandOption = $this->attributeOptionRepository->create([
                'admin_name'   => $brandName,
                'attribute_id' => $brandAttribute->id,
                'sort_order'   => 0,
            ]);
        }

        return $brandOption->id;
    }
}