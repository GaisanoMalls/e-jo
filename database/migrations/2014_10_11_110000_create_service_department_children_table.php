<?php

use App\Models\ServiceDepartment;
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
        Schema::create('service_department_children', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->constrained('service_departments')->cascadeOnDelete();
            $table->string('name');
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
        Schema::dropIfExists('service_department_children');
    }
};
