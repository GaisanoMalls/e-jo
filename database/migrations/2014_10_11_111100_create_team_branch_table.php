<?php

use App\Models\Branch;
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
        Schema::create('team_branch', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'team_id')->constrained();
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
        Schema::dropIfExists('team_branch');
    }
};