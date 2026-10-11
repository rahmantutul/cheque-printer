<?php
declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::table('cheques', function (Blueprint $table): void { $table->text('amount_words_line_1')->nullable()->after('amount_in_words'); $table->text('amount_words_line_2')->nullable()->after('amount_words_line_1'); }); }
    public function down(): void { Schema::table('cheques', function (Blueprint $table): void { $table->dropColumn(['amount_words_line_1', 'amount_words_line_2']); }); }
};
