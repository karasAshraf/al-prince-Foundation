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
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
        $table->string('title_ar');
        $table->string('title_en')->nullable();
        $table->string('slug')->unique();
        $table->text('description_ar')->nullable();
        $table->text('description_en')->nullable();
        $table->text('goal_ar')->nullable();
        $table->text('goal_en')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->enum('project_status', ['ongoing', 'completed'])->default('ongoing');

        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->string('image')->nullable();
        $table->string('external_link')->nullable();

        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
