<?php

use App\Models\Field;
use App\Models\HelpTopic;
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
        Schema::create('help_topic_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id')->constrained();
            $table->foreignIdFor(Field::class, 'field_id')->constrained();
            $table->boolean('is_enabled')->default(false);
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
        Schema::dropIfExists('help_topic_fields');
    }
};
