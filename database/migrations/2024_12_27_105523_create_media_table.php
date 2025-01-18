<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->morphs('mediable');
            $table->string('path')->nullable();
            $table->string('alt')->nullable();
            $table->nullableTimestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
