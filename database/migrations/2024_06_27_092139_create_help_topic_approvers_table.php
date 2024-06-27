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
        Schema::create('help_topic_approvers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('help_topic_configuration_id');
            $table->unsignedBigInteger('help_topic_id');
            $table->unsignedInteger('level');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('help_topic_approvers');
    }
};
