<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationRequest extends Model
{
    use HasFactory;

    protected $table = 'table_quotation_request';
    protected $primaryKey = 'request_id';
    protected $fillable = [
        'request_id',
        'notes',
        'user_id',
        'product_option_id',
        'status_id'
    ];

    public function productOption()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }
}
