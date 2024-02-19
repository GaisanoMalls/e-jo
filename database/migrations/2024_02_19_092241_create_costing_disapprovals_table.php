<?php

use App\Models\TicketCosting;
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
        Schema::create('costing_disapprovals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TicketCosting::class, 'tick_costing_id')->constrained('ticket_costings')->cascadeOnDelete();
            $table->text('reason');
            $table->dateTime('date_approved');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costing_disapprovals');
    }
};
