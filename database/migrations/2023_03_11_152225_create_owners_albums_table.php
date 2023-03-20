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
        Schema::create('owners_albums', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->index();
            $table->unsignedBigInteger('owner_id');
            $table->string('owner_name', 64);
            $table->string('owner_attribute', 64);
            $table->primary(['album_id', 'owner_id', 'owner_name', 'owner_attribute']);
            $table->timestamps();

            $table->foreign('album_id')->references('id')->on('albums')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners_albums');
    }
};
