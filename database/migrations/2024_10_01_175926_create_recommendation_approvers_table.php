<?php

use App\Models\RecommendationApprovalLevel;
use App\Models\User;
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
        Schema::create('recommendation_approvers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RecommendationApprovalLevel::class, 'approval_level_id')
                ->constrained('recommendation_approval_levels')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'approver_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recommendation_approvers');
    }
};
