<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'table_role';

    // Protected fillable attributes
    protected $fillable = [
        'role_name'
    ];

    // Accessors
    public function getRoleNameAttribute($value)
    {
        return ucfirst($value); // Example transformation, makes the first letter uppercase
    }

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class); // One-to-many relationship
    }

    // Scopes
    public function scopeWithUsers($query)
    {
        return $query->with('users'); // Eager load users when querying roles
    }
}
