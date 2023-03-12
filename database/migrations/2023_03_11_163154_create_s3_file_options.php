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
        Schema::create('s3_file_options', function (Blueprint $table) {
            $table->unsignedBigInteger('mediafile_id')->index();
            $table->string('bucket', 64);
            $table->string('prefix', 128);
            $table->primary(['mediafile_id', 'bucket', 'prefix']);

            $table->foreign('mediafile_id')->references('id')->on('mediafiles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s3_file_options');
    }
};
