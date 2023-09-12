<?php

use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\PriorityLevel;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'agent_id')->nullable();
            $table->foreignIdFor(Branch::class, 'branch_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Team::class, 'team_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id');
            $table->foreignIdFor(Status::class, 'status_id');
            $table->foreignIdFor(PriorityLevel::class, 'priority_level_id');
            $table->foreignIdFor(ServiceLevelAgreement::class, 'sla_id')->nullable();
            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->longText('description');
            $table->longText('issue_summary')->nullable();
            $table->enum('approval_status', ['for_approval', 'approved', 'disapproved']);
            $table->json('service_department_head_approver');
            $table->json('bu_head_approver')->nullable();
            $table->boolean('head_approval_completed')->default(false);
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
        Schema::dropIfExists('tickets');
    }
}