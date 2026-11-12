<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('description');
            $table->string('cv_path')->nullable()->after('photo_path');
            $table->string('projects_subtitle')->nullable()->after('organization_subtitle');
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('role')->nullable();
            $table->string('period', 100)->nullable();
            $table->string('summary', 500)->nullable();
            $table->text('description')->nullable();
            $table->json('tech_stack')->nullable();
            $table->string('image_path')->nullable();
            $table->string('url')->nullable();
            $table->string('repo_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'cv_path', 'projects_subtitle']);
        });
    }
};
