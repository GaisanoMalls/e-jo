<?php

use App\Models\Field;
use App\Models\HelpTopic;
use App\Models\HelpTopicField;
use App\Models\Ticket;
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
        Schema::create('field_entry_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HelpTopicField::class, 'help_topic_field_id')->constrained();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained();
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id')->constrained();
            $table->foreignIdFor(Field::class, 'field_id')->constrained();
            $table->longText('value');
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
        Schema::dropIfExists('field_entry_values');
    }
};