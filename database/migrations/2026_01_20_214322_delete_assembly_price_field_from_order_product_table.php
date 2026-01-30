<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_product', function (Blueprint $table): void {
            $table->dropColumn('assembly_price');
        });
    }

    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table): void {
            $table->integer('assembly_price')->default(0)->nullable();
        });
    }
};
