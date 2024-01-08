<?php

use App\Models\HelpTopic;
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
        Schema::create('ticket_approval', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_need_level_of_approval')->default(false);
            $table->json('level_1_approver')->nullable();
            $table->json('level_2_approver')->nullable();
            $table->boolean('is_all_approved')->default(false);
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
        Schema::dropIfExists('ticket_approval');
    }
};
