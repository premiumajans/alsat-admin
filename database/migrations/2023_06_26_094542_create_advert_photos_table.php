<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advert_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('advert_id')->unsigned();
            $table->longText('photo');
            $table->boolean('main_photo')->default(0);
            $table->foreign('advert_id')
                ->references('id')
                ->on('adverts')
                ->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('advert_photos');
    }
};
