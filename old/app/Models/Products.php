<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Aimeos\MShop\Common\Item\ListsRef\Traits as ListsRefTrait;

class Products extends Model
{
    // Integro Aimeos ListRefTrait
    use ListsRefTrait;

    protected $table = 'combined_pharma_data_table';
    protected $primaryKey = 'id';
    protected $fillable = [
        'WEBSITE_ID',
        'DATE',
        'MINSAN_CODE',
        'title',
        'brand',
        'image_url',
        'category',
        'price_original',
        'price_discounted',
        'availability',
        'url_page',
        'review_count',
        'date_added',
        'date_updated',
        'source_table'
    ];

    public function toAimeosProduct()
    {
        return [
            'product.code' => $this->MINSAN_CODE,
            'product.label' => $this->title,
            'product.status' => $this->availability ? 1 : 0,
            'price.value' => $this->price_discounted ?? $this->price_original,
            'media.url' => $this->image_url,
            'catalog.code' => $this->category,
            'order.base.product.status' => 1
        ];
    }
}
