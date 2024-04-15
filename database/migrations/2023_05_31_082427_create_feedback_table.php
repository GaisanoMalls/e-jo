<?php

use App\Enums\TicketRatingEnum;
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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->enum('rating', [TicketRatingEnum::TERRIBLE, TicketRatingEnum::BAD, TicketRatingEnum::GOOD, TicketRatingEnum::VERY_GOOD, TicketRatingEnum::EXCELLENT]);
            $table->enum('had_issues_encountered', ['Yes', 'No']);
            $table->longText('description');
            $table->longText('suggestion')->nullable();
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
        Schema::dropIfExists('feedback');
    }
};
