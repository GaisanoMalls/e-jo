<?php

use App\Models\TicketCustomFormField;
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
        Schema::create('ticket_custom_form_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TicketCustomFormField::class, 'ticket_custom_form_field_id')->constrained('ticket_custom_form_fields')->cascadeOnDelete();
            $table->string('file_attachment');
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
        Schema::dropIfExists('ticket_custom_form_files');
    }
};
