<?php

use App\Models\IctRecommendation;
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
        Schema::create('ict_recommendation_approval_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(IctRecommendation::class, 'ict_recommendation_id')->constrained('ict_recommendations')->cascadeOnDelete();
            $table->integer('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ict_recommendation_approval_levels');
    }
};
