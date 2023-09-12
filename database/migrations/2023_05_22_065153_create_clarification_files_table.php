<?php

use App\Models\Clarification;
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
        Schema::create('clarification_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clarification::class, 'clarification_id')->constrained();
            $table->string('file_attachment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clarification_files');
    }
};