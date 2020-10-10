<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('eloquent-images.table_names.images'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->timestamps();
        });

        Schema::create(config('eloquent-images.table_names.model_has_images'), function (Blueprint $table) {
            $table->unsignedBigInteger('image_id');
            $table->morphs('imagegable');

            $table->primary(['image_id', config('eloquent-images.column_names.imagegable_morph_key'), 'imagegable_type']);

            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('eloquent-images.table_names.model_has_images'));
        Schema::dropIfExists(config('eloquent-images.table_names.images'));
    }
}
