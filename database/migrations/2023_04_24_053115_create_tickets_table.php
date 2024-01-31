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
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'agent_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Branch::class, 'branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->constrained('service_departments')->cascadeOnDelete();
            $table->foreignIdFor(HelpTopic::class, 'help_topic_id')->constrained('help_topics')->cascadeOnDelete();
            $table->foreignIdFor(Status::class, 'status_id')->constrained('statuses')->cascadeOnDelete();
            $table->foreignIdFor(PriorityLevel::class, 'priority_level_id')->constrained('priority_levels')->cascadeOnDelete();
            $table->foreignIdFor(ServiceLevelAgreement::class, 'service_level_agreement_id')->nullable()->constrained('service_level_agreements')->cascadeOnDelete();
            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->longText('description');
            $table->longText('issue_summary')->nullable();
            $table->string('approval_status');
            $table->dateTime('svcdept_date_approved')->nullable();
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