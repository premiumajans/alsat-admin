<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('chat_room_id')->unsigned();
            $table->longText('content');
            $table->boolean('seen_status')->default(false);
            $table->dateTime('seen_time')->nullable();
            $table->dateTime('created_at')->default(\Carbon\Carbon::now());
            $table->boolean('status')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
