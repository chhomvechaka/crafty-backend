<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

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

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if the user has an admin role.
     *
     * @return bool
     */
    public function isAdmin() {
        return $this->role->role_name === 'admin';
    }

}
