<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductOption extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'table_product_option';
    protected $primaryKey = 'product_option_id';
    protected $fillable = [
        'design_element',
        'product_id',
        'user_id',
        'is_requested'
    ];

    protected $casts = [
        'design_element' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
