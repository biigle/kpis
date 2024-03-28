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
        Schema::create('kpis_unique_users', function (Blueprint $table) {
            $table->date('date')->unique();
            $table->bigInteger('value');
        });

        Schema::create('kpis_users', function (Blueprint $table) {
            $table->date('date')->unique();
            $table->bigInteger('value');
        });

        Schema::create('kpis_storage_usage', function (Blueprint $table) {
            $table->date('date')->unique();
            $table->bigInteger('value');
        });

        Schema::create('kpis_actions', function (Blueprint $table) {
            $table->date('date')->unique();
            $table->bigInteger('value');
        });

        Schema::create('kpis_visits', function (Blueprint $table) {
            $table->date('date')->unique();
            $table->bigInteger('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis_unique_users');
        Schema::dropIfExists('kpis_users');
        Schema::dropIfExists('kpis_storage_usage');
        Schema::dropIfExists('kpis_actions');
        Schema::dropIfExists('kpis_visits');
    }
};
