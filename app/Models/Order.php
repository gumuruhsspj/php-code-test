<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Add trait for factory support (useful for testing and seeding)
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be set via create() or fill() methods.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',  // Reference to the user who owns this order
    ];
}
