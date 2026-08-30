<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_page_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('home_page_sections', 'type')) {
                $table->string('type')->default('home_section')->after('id');
            }
        });

        try {
            DB::statement("ALTER TABLE `home_page_sections` MODIFY `data` JSON NULL;");
        } catch (\Throwable $e) {
            try {
                DB::statement("ALTER TABLE `home_page_sections` MODIFY `data` TEXT NULL;");
            } catch (\Throwable $e2) {}
        }

        try {
            DB::statement("ALTER TABLE `home_page_sections` MODIFY `title_ar` VARCHAR(255) NULL;");
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_page_sections', function (Blueprint $table) {
            if (Schema::hasColumn('home_page_sections', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
