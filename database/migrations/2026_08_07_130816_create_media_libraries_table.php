<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();

            // تصنيف الملف: تقارير سنوية، منشورات، أبحاث...
            $table->string('category')->nullable()->index();

            // مسار الملف المرفوع (fallback column، زي باقي الموديولز)
            $table->string('file')->nullable();

            // رابط خارجي بديل عن رفع ملف
            $table->string('external_link')->nullable();

            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_libraries');
    }
};