<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizational_structures', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image_ar')->nullable(); // path/disk key for Arabic chart image
            $table->string('image_en')->nullable(); // path/disk key for English chart image (optional)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizational_structures');
    }
};