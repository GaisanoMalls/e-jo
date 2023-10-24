<?php

use App\Models\HelpTopic;
use App\Models\Level;
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
        Schema::create('help_topic_level', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Level::class, 'level_id')->nullable()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('help_topic_level');
    }
};