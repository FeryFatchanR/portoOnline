<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('logo_text');
            $table->string('logo_path')->nullable()->after('company_name');
            $table->string('tagline')->nullable()->after('logo_path');
            $table->string('services_subtitle')->nullable()->after('skills_subtitle');
            $table->string('team_subtitle')->nullable()->after('services_subtitle');
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon', 30)->default('code');
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('bio', 500)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('services');

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'logo_path', 'tagline', 'services_subtitle', 'team_subtitle']);
        });
    }
};
