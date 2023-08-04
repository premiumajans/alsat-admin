<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advert_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('advert_id')->unsigned();
            $table->integer('phone_view')->default(0);

            $table->foreign('advert_id')->references('id')
                ->on('adverts')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('advert_views');
    }
};
