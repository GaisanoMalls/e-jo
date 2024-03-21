<?php

use App\Models\Department;
use App\Models\ServiceDepartment;
use App\Models\ServiceDepartmentChildren;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->nullable()->constrained('service_departments')->cascadeOnDelete();
            $table->foreignIdFor(ServiceDepartmentChildren::class, 'service_dept_child_id')->nullable()->constrained('service_department_children')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('slug')->unique();
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
        Schema::dropIfExists('teams');
    }
}