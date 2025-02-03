<?php

use App\Enums\SubtaskStatusEnum;
use App\Models\Team;
use App\Models\Ticket;
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
        Schema::create('ticket_subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignIdFor(Team::class, 'team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('task_name');
            $table->enum('status', SubtaskStatusEnum::toArray());
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
        Schema::dropIfExists('ticket_subtasks');
    }
};
