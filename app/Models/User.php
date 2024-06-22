<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use InteractsWithMedia;

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'table_users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'role_id', 'firstname', 'lastname', 'email', 'phone_number', 'address', 'firebase_uid','password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');  // specify the foreign key and related key
    }

    /**
     * Check if the user has an admin role.
     *
     * @return bool
     */
//    public function isAdmin() {
//        return $this->role->role_name === 'admin';
//    }
    public function stores()
    {
        return $this->hasMany(Store::class, 'user_id', 'user_id'); // Foreign key on table_stores, primary key on table_users
    }

//    public function isSeller() {
//        return $this->role->role_name === 'seller';
//    }
//    public function isBuyer() {
//        return $this->role->role_name === 'buyer';
//    }
// In User.php (User model)
    public function isSeller() {
        return $this->role->role_name === 'seller' || $this->role_id === 2;
    }

    public function isBuyer() {
        return $this->role->role_name === 'buyer' || $this->role_id === 1;
    }

    public function isAdmin() {
        return $this->role->role_name === 'admin' || $this->role_id === 3;
    }

}
