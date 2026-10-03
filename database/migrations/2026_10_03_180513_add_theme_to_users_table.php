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
        if (Schema::hasColumn('users', 'theme')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('theme')->default('light');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('users', 'theme')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('theme');
        });
    }
};
