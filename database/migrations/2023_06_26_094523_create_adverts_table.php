<?php

use App\Http\Enums\AdvertStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('user_id')->unsigned();
            $table->integer('view_count')->default(0);
            $table->integer('priority')->default(1);
            $table->boolean('vip_status')->default(0);
            $table->boolean('premium_status')->default(0);
            $table->boolean('market')->default(0);
            $table->integer('phone_view')->default(0);
            $table->boolean('admin_status')->default(0);
            $table->integer('owner_type')->default(1);
            $table->integer('advert_status')->default(AdvertStatusEnum::PENDING);
            $table->timestamp('approved_time')->nullable();
            $table->dateTime('end_time')->default(Carbon::now()->addMonth());
            $table->timestamps();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adverts');
    }
};
