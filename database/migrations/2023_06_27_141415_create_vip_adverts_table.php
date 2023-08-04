<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vip_adverts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->unsigned();
            $table->integer('vip');
            $table->string('start_time');
            $table->string('end_time');
            $table->boolean('status')->default(1);
            $table->foreign('advert_id')
                ->references('id')
                ->on('adverts')
                ->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('vip_adverts');
    }
};
