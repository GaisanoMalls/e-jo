<?php

use App\Models\Department;
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
        Schema::create('help_topic_non_config', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id')->constrained('help_topics')->cascadeOnDelete();
            $table->foreignIdFor(Department::class, 'bu_department_id')->constrained('departments')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('help_topic_non_config');
    }
};
