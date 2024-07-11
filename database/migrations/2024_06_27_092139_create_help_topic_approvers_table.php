<?php

use App\Models\HelpTopic;
use App\Models\HelpTopicConfiguration;
use App\Models\User;
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
        Schema::create('help_topic_approvers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HelpTopicConfiguration::class, 'help_topic_configuration_id')->constrained('help_topic_configurations')->cascadeOnDelete();
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id')->constrained('help_topics')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_approved')->default(false);
            $table->unsignedInteger('level');
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
