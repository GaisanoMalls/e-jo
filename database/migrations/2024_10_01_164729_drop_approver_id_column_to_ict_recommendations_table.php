<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ict_recommendations', function (Blueprint $table) {
            if (Schema::hasColumn('ict_recommendations', 'approver_id')) {
                $table->dropColumn('approver_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ict_recommendations', function (Blueprint $table) {
            // No changes neeeded for rollback.
        });
    }
};
