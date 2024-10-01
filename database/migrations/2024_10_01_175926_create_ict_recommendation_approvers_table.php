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
            $table->foreignIdFor(IctRecommendationApprovalLevel::class, 'ict_recommendation_approval_level_id');
            $table->foreignIdFor(User::class, 'approver_id')->constrained('users')->cascadeOnDelete();
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
        Schema::dropIfExists('ict_recommendation_approvers');
    }
};
