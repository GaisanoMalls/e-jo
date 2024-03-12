<?php

use App\Enums\SpecialProjectStatusEnum;
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
        Schema::create('ticket_special_project_status', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->enum('costing_and_planning_status', [SpecialProjectStatusEnum::DONE->value])->nullable();
            $table->enum('purchasing_status', [SpecialProjectStatusEnum::ON_ORDERED->value, SpecialProjectStatusEnum::DELIVERED->value])->nullable();
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
        Schema::dropIfExists('ticket_special_project_status');
    }
};
