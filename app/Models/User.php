<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // Add traits for API token management, factory support, and notifications
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * 
     * These fields can be set via create() or fill() methods.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',       // User's full name
        'email',      // User's email address
        'password',   // User's password (hashed)
        'role',       // Role of the user (e.g., admin, manager, user)
        'active'      // Flag to indicate if user account is active
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * These fields will be excluded when the model is converted to arrays or JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',        // Hide password for security
        'remember_token',  // Hide remember_token used by authentication
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * Useful to automatically convert dates, booleans, and hashes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',  // Convert email verification timestamp to DateTime object
        'password' => 'hashed',             // Automatically hash password when set
    ];
}
