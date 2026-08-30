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
        Schema::table('partners', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
        });

        Schema::table('media_libraries', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
        });

        Schema::table('about_sections', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->text('external_link')->nullable()->change();
            $table->text('external_link_ar')->nullable()->change();
            $table->text('external_link_en')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
        });

        Schema::table('media_libraries', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
        });

        Schema::table('about_sections', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('external_link')->nullable()->change();
            $table->string('external_link_ar')->nullable()->change();
            $table->string('external_link_en')->nullable()->change();
        });
    }
};
