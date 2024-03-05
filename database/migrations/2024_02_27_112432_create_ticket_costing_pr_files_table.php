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
        Schema::create('ticket_costing_pr_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TicketCosting::class, 'ticket_costing_id')->constrained('ticket_costings')->cascadeOnDelete();
            $table->string('file_attachment')->nullable(false);
            $table->boolean('is_approved_level_1_approver')->default(false);
            $table->boolean('is_approved_level_2_approver')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_costing_pr_files');
    }
};
