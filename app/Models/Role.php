<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'table_role';  // Ensure this matches the actual table name in your database
    protected $primaryKey = 'role_id';  // Set the primary key to the correct column name
    //protected $fillable = ['role_name']; // Fillable fields
    public $incrementing = false;

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

}
