<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cheque_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('bank_id')->index()->constrained('banks')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('background_image_path', 255)->nullable();
            $table->decimal('width_mm', 8, 2)->default(203.00);
            $table->decimal('height_mm', 8, 2)->default(90.00);
            $table->json('field_positions')->nullable();
            $table->foreignId('created_by')->nullable()->index()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cheque_templates');
    }
};
