<?php

use App\Models\ServiceDepartment;
use App\Models\ServiceDepartmentChildren;
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
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->constrained('service_departments')->cascadeOnDelete();
            $table->foreignIdFor(ServiceDepartmentChildren::class, 'service_dept_child_id')->nullable()->constrained('service_department_children')->cascadeOnDelete();
            $table->foreignIdFor(Team::class, 'team_id')->nullable()->constrained('teams')->cascadeOnDelete();
            $table->foreignIdFor(ServiceLevelAgreement::class, 'service_level_agreement_id')->constrained('service_level_agreements')->cascadeOnDelete();
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