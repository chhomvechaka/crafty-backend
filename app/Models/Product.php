<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'table_product';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'product_description',
        'base_price',
        'stock',
        'tag_id',
        'store_id'
    ];

    /**
     * Define the media collection for product images.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')->useDisk('public');
    }
}
