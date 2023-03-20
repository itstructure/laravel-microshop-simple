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
        Schema::create('mediafiles', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 128);
            $table->string('type', 64);
            $table->text('url');
            $table->string('alt', 128)->nullable();
            $table->integer('size');
            $table->string('title', 128)->nullable();
            $table->text('description')->nullable();
            $table->json('thumbs')->default('{}');
            $table->string('disk', 64);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mediafiles');
    }
};
