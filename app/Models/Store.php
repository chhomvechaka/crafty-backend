<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = 'table_stores'; // Specify the table name if it doesn't follow Laravel's naming convention

    protected $primaryKey = 'store_id'; // Specify the primary key column

    protected $fillable = [
        'store_name',
        'store_description',
        'store_contact',
        'store_address',
        'store_logo_path',
        'user_id'
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'store_id');
    }
}
