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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->createCompanyIdField();
            $table->string('name');
            $table->string('description')->default('');
            $table->string('bill_period')->default('monthly');
            $table->decimal('price', 10, 2)->default(0.);
            $table->boolean('active')->default(true);
            $table->createdUpdatedTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
            // $table->unsignedTinyInteger('notify_before')->default(7);
