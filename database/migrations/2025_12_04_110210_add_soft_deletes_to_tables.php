<?php

declare(strict_types=1);

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
        Schema::table('users', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('product_spare_parts', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('disassemblies', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('addresses', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('order_product', function (Blueprint $table): void {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('product_spare_parts', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('disassemblies', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('addresses', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('order_product', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });
    }
};
