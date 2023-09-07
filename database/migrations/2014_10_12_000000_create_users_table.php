<?php

use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class, 'branch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Department::class, 'department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Role::class, 'role_id')->constrained()->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_highest_approver')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};