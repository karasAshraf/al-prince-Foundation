<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_page_sections', function (Blueprint $table) {
            if (Schema::hasColumn('home_page_sections', 'label')) {
                $table->renameColumn('label', 'label_ar');
            }
        });

        Schema::table('home_page_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('home_page_sections', 'label_en')) {
                $table->string('label_en')->nullable()->after('label_ar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_page_sections', function (Blueprint $table) {
            if (Schema::hasColumn('home_page_sections', 'label_en')) {
                $table->dropColumn('label_en');
            }
        });

        Schema::table('home_page_sections', function (Blueprint $table) {
            if (Schema::hasColumn('home_page_sections', 'label_ar')) {
                $table->renameColumn('label_ar', 'label');
            }
        });
    }
};
