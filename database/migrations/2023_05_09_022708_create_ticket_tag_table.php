<?php

use App\Models\Tag;
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
        Schema::create('ticket_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained();
            $table->foreignIdFor(Tag::class, 'tag_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_tag');
    }
};