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
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('store_group_id')->nullable()->constrained('store_groups')->onDelete('set null');
            $table->dropColumn('store_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('store_group')->nullable();
            $table->dropForeign(['store_group_id']);
            $table->dropColumn('store_group_id');
        });
    }
};
