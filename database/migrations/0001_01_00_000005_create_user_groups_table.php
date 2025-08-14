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
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->createCompanyIdField();
            $table->string('name');
            $table->string('description')->nullable();
            $table->createdUpdatedTimestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable()->after('id');
            $table->foreign('group_id')
                ->references('id')->on('user_groups')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_groups');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
    }
};
