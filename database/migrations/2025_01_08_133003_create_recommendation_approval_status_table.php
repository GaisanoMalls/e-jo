<?php

use App\Enums\RecommendationApprovalStatusEnum;
use App\Models\Recommendation;
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
        Schema::create('recommendation_approval_status', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Recommendation::class, 'recommendation_id')->constrained('recommendations')->cascadeOnDelete();
            $table->enum('approval_status', RecommendationApprovalStatusEnum::toArray())->default(RecommendationApprovalStatusEnum::PENDING->value);
            $table->longText('disapproved_reason')->nullable();
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recommendation_approval_status');
    }
};
