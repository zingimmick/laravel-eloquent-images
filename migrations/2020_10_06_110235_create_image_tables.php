<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            config('eloquent-images.table_names.images'),
            static function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->string('url');
                $table->timestamps();
            }
        );

        Schema::create(
            config('eloquent-images.table_names.model_has_images'),
            static function (Blueprint $table): void {
                $table->unsignedBigInteger('image_id');
                $table->morphs('imageable');
                $table->tinyInteger('priority')
                    ->default(0);
                $table->primary(
                    ['image_id', config('eloquent-images.column_names.imageable_morph_key'), 'imageable_type']
                );
                $table->foreign('image_id')
                    ->references('id')
                    ->on('images')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('eloquent-images.table_names.model_has_images'));
        Schema::dropIfExists(config('eloquent-images.table_names.images'));
    }
}
