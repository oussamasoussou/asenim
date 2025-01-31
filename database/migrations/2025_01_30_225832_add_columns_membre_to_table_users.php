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
        Schema::table('users', function (Blueprint $table) {
            $table->string('institution')->nullable();
            $table->string('grade')->nullable();
            $table->string('orcid')->nullable();
            $table->string('function')->nullable();
            $table->text('biography')->nullable();
            $table->text('activities')->nullable();
            $table->integer('level')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
