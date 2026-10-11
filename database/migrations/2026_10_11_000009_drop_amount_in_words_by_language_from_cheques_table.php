<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('cheques', 'amount_in_words_by_language')) {
            Schema::table('cheques', function (Blueprint $table): void {
                $table->dropColumn('amount_in_words_by_language');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('cheques', 'amount_in_words_by_language')) {
            Schema::table('cheques', function (Blueprint $table): void {
                $table->json('amount_in_words_by_language')->nullable()->after('amount_in_words');
            });
        }
    }
};
