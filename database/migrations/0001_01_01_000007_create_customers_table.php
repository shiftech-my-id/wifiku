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
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('restrict');
            $table->string('code');
            $table->string('name');
            $table->string('id_card_number')->default('');
            $table->string('email')->default('');
            $table->string('phone')->default('');
            $table->string('wa')->default('');
            $table->string('address')->default('');
            $table->boolean('active')->default(true);
            $table->decimal('balance', 12, 2)->default(0.);
            $table->date('installation_date')->nullable();
            $table->string('lat_long')->nullable();
            $table->text('notes')->nullable();

            $table->createdUpdatedDeletedTimestamps();
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
