<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_topic_costings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('help_topic_id');
            $table->json('costing_approvers');
            $table->decimal('amount', 15, 2);
            $table->json('final_costing_approvers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('help_topic_costing');
    }
};
