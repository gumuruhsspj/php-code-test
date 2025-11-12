<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the 'orders' table with a reference to the user.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            // Primary key
            $table->id();

            // Foreign key linking to the users table
            // Cascades delete if the user is removed
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // created_at and updated_at timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the 'orders' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
