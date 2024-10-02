<?php

use App\Models\IctRecommendationApprovalLevel;
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
        Schema::create('ict_recommendation_approvers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(IctRecommendationApprovalLevel::class, 'approval_level_id')
                ->constrained('ict_recommendation_approval_levels')->cascadeOnDelete();
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
        Schema::dropIfExists('ict_recommendation_approvers');
    }
};
