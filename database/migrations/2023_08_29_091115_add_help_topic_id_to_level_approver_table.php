<?php

use App\Models\HelpTopic;
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
        Schema::table('level_approver', function (Blueprint $table) {
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('level_approver', function (Blueprint $table) {
            $table->dropColumn('help_topic_id');
        });
    }
};