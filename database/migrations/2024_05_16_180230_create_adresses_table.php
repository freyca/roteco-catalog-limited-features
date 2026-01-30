<?php

declare(strict_types=1);

use App\Models\User;
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
        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->string('address_type');
            $table->string('name');
            $table->string('surname');
            $table->string('bussiness_name')->nullable();
            $table->string('email');
            $table->string('financial_number')->nullable();
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->unsignedInteger('zip_code');
            $table->string('country');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
