<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('social_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('nickname')->nullable();
            $table->string('provider', 128)->nullable();
            $table->string('provider_id')->unique()->nullable();
            $table->text('token')->nullable(); // Text because Facebook tokens can be greater than 255 characters
            $table->string('avatar')->nullable();
            $table->index('provider');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('social_identities');
    }
};
