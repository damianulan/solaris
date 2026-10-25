<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contract_type_id')->constrained()->restrictOnDelete();
            $table->string('employee_number')->nullable();
            $table->string('position_title')->nullable();
            $table->date('started_on');
            $table->date('ended_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'started_on', 'ended_on']);
            $table->index(['company_id', 'started_on', 'ended_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employments');
    }
};
