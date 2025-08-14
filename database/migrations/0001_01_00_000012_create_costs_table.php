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
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->createCompanyIdField();
            $table->foreignId('category_id')->nullable()->constrained('cost_categories')->onDelete('cascade');
            $table->string('description', 100);
            $table->datetime('datetime')->nullable();
            $table->decimal('amount', 12, 2)->default(0.);
            $table->text('notes')->nullable();
            $table->createdUpdatedTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costs');
    }
};
