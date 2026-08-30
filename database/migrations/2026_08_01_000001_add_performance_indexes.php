<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds database indexes for frequently filtered/ordered columns that were missing.
     * These indexes accelerate WHERE and ORDER BY clauses on all dashboard list pages.
     */
    public function up(): void
    {
        // Programs: filtered by status, ordered by order, soft-deleted
        Schema::table('programs', function (Blueprint $table) {
            $table->index('status', 'programs_status_idx');
            $table->index('order', 'programs_order_idx');
            $table->index('deleted_at', 'programs_deleted_at_idx');
        });

        // Projects: filtered by program_id (FK index exists but naming explicitly),
        // project_status, status; soft-deleted
        Schema::table('projects', function (Blueprint $table) {
            $table->index('project_status', 'projects_project_status_idx');
            $table->index('status', 'projects_status_idx');
            $table->index('deleted_at', 'projects_deleted_at_idx');
        });

        // News: filtered by status, published_at; soft-deleted
        Schema::table('news', function (Blueprint $table) {
            $table->index('status', 'news_status_idx');
            $table->index('published_at', 'news_published_at_idx');
            $table->index('deleted_at', 'news_deleted_at_idx');
        });

        // Governance Documents: filtered by fiscal_year, is_active, category
        Schema::table('governance_documents', function (Blueprint $table) {
            $table->index('fiscal_year', 'gov_docs_fiscal_year_idx');
            $table->index('is_active', 'gov_docs_is_active_idx');
            $table->index('category', 'gov_docs_category_idx');
        });

        // Team Members: filtered by type, ordered by order, filtered by is_active
        Schema::table('team_members', function (Blueprint $table) {
            $table->index(['type', 'order'], 'team_members_type_order_idx');
            $table->index('is_active', 'team_members_is_active_idx');
        });

        // Home Page Sections: filtered by is_active, ordered by order
        Schema::table('home_page_sections', function (Blueprint $table) {
            $table->index(['is_active', 'order'], 'home_sections_is_active_order_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropIndex('programs_status_idx');
            $table->dropIndex('programs_order_idx');
            $table->dropIndex('programs_deleted_at_idx');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_project_status_idx');
            $table->dropIndex('projects_status_idx');
            $table->dropIndex('projects_deleted_at_idx');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('news_status_idx');
            $table->dropIndex('news_published_at_idx');
            $table->dropIndex('news_deleted_at_idx');
        });

        Schema::table('governance_documents', function (Blueprint $table) {
            $table->dropIndex('gov_docs_fiscal_year_idx');
            $table->dropIndex('gov_docs_is_active_idx');
            $table->dropIndex('gov_docs_category_idx');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropIndex('team_members_type_order_idx');
            $table->dropIndex('team_members_is_active_idx');
        });

        Schema::table('home_page_sections', function (Blueprint $table) {
            $table->dropIndex('home_sections_is_active_order_idx');
        });
    }
};
