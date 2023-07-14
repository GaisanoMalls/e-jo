<?php

use App\Models\Branch;
use App\Models\ServiceDepartment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_service_department', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->constrained();
            $table->foreignIdFor(Branch::class, 'branch_id')->constrained();
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
        Schema::dropIfExists('branch_service_department');
    }
};
