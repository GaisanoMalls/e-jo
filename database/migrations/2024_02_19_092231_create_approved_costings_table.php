<?php

use App\Models\SpecialProjectAmountApproval;
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
        Schema::create('approved_costings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SpecialProjectAmountApproval::class, 'special_project_amount_approval_id')->constrained('special_project_amount_approvals')->cascadeOnDelete();
            $table->dateTime('approved_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_costings');
    }
};
