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
        Schema::table('surveys', function (Blueprint $table) {
            $table->string('type_ar')->nullable()->after('description_en');
            $table->string('type_en')->nullable()->after('type_ar');
        });

        // Migrate existing data
        \Illuminate\Support\Facades\DB::table('surveys')->get()->each(function ($survey) {
            \Illuminate\Support\Facades\DB::table('surveys')
                ->where('id', $survey->id)
                ->update([
                    'type_ar' => $survey->type ?: 'general',
                    'type_en' => $survey->type ?: 'general',
                ]);
        });

        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->string('type')->nullable()->after('description_en');
        });

        // Rollback existing data
        \Illuminate\Support\Facades\DB::table('surveys')->get()->each(function ($survey) {
            \Illuminate\Support\Facades\DB::table('surveys')
                ->where('id', $survey->id)
                ->update([
                    'type' => $survey->type_ar ?: $survey->type_en,
                ]);
        });

        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['type_ar', 'type_en']);
        });
    }
};
