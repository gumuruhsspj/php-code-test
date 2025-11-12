<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the 'users' table with required fields.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            // Primary key
            $table->id();

            // User email, unique
            $table->string('email')->unique();

            // User password
            $table->string('password');

            // User full name
            $table->string('name', 255);

            // User role: admin, manager, or user
            $table->enum('role', ['admin', 'manager', 'user'])->default('user');

            // Active status flag
            $table->boolean('active')->default(true);

            // created_at and updated_at timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the 'users' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
