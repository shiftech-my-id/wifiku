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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('owner_name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            $table->datetime('registration_datetime')->nullable();
            $table->datetime('activation_datetime')->nullable();
            $table->string('activation_code')->nullable();
            $table->boolean('active')->default(true);

            $table->createdUpdatedDeletedTimestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
