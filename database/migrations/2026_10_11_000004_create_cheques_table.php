<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cheques', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('bank_id')->index()->constrained('banks')->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->index()->constrained('cheque_templates')->nullOnDelete();
            $table->string('payee_name', 255)->index();
            $table->decimal('amount', 15, 2);
            $table->text('amount_in_words')->nullable();
            $table->date('cheque_date')->index();
            $table->string('memo', 255)->nullable();
            $table->enum('status', ['draft', 'printed'])->default('draft')->index();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
