<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('personal_number')->unique()->nullable();
            $table->string('image_path')->nullable();
            $table->string('address')->nullable();
            $table->longText('about')->nullable();
            $table->string('website')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
