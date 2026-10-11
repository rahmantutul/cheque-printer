<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cheque_templates', function (Blueprint $table): void {
            $table->unsignedTinyInteger('font_size_pt')->default(12)->after('height_mm');
        });
    }

    public function down(): void
    {
        Schema::table('cheque_templates', function (Blueprint $table): void {
            $table->dropColumn('font_size_pt');
        });
    }
};
