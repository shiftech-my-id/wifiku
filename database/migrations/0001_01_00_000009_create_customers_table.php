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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->createCompanyIdField();
            $table->unsignedInteger('customer_id')->default(0);
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('restrict');
            $table->string('name');
            $table->string('id_card_number')->nullable()->default('');
            $table->string('email')->nullable()->default('');
            $table->string('phone')->nullable()->default('');
            $table->string('wa')->nullable()->default('');
            $table->string('address')->nullable()->default('');
            $table->boolean('active')->default(true);
            $table->decimal('balance', 12, 2)->nullable()->default(0.);
            $table->date('installation_date')->nullable();
            $table->string('lat_long')->nullable();
            $table->text('notes')->nullable();

            $table->createdUpdatedTimestamps();
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
