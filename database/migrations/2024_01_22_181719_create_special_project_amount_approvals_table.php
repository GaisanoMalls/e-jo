<?php

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
        Schema::create('special_project_amount_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class, 'ticket_id')->nullable()->constrained('tickets')->cascadeOnDelete();
            $table->json('service_department_admin_approver')->nullable();
            $table->json('fpm_coo_approver')->nullable();
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
        Schema::dropIfExists('special_project_amount_approvals');
    }
};
