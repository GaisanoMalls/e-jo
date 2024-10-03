<?php

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
        Schema::create('recommendation_approval_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Recommendation::class, 'recommendation_id')->constrained('recommendations')->cascadeOnDelete();
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
        Schema::dropIfExists('recommendation_approval_levels');
    }
};
