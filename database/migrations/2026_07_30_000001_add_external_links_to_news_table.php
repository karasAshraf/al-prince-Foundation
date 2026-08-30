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
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'external_link_ar')) {
                $table->string('external_link_ar')->nullable()->after('external_link');
            }
            if (!Schema::hasColumn('news', 'external_link_en')) {
                $table->string('external_link_en')->nullable()->after('external_link_ar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['external_link_ar', 'external_link_en']);
        });
    }
};
