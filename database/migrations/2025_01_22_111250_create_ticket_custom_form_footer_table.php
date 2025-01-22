<?php

use App\Models\Form;
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
        Schema::create('ticket_custom_form_footer', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignIdFor(Form::class, 'form_id')->constrained('forms')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'requested_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'noted_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'approved_by')->nullable()->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_custom_form_footer');
    }
};
