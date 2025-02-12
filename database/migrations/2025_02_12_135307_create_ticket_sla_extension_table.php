<?php

use App\Enums\TicketSlaExtensionStatusEnum;
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
        Schema::create('ticket_sla_extension', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'requested_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', TicketSlaExtensionStatusEnum::toArray())->default(TicketSlaExtensionStatusEnum::REQUESTING->value);
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
        Schema::dropIfExists('ticket_sla_extension');
    }
};
