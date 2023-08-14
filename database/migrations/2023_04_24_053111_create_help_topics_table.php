<?php

use App\Models\ApprovalLevel;
use App\Models\Department;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\Team;
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
        Schema::create('help_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Team::class, 'team_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ServiceLevelAgreement::class, 'sla_id');
            $table->string('name');
            $table->string('slug');
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
        Schema::dropIfExists('help_topics');
    }
};