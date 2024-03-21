<?php

use App\Models\ServiceDepartmentChildren;
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
        Schema::create('team_service_department_children', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignIdFor(ServiceDepartmentChildren::class, 'service_dept_child_id')->constrained('service_department_children')->cascadeOnDelete();
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
        Schema::dropIfExists('team_service_department_children');
    }
};
