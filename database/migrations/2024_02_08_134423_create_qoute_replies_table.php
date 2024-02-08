<?php

use App\Models\Reply;
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
        Schema::create('qoute_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'qouted_by')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Reply::class, 'reply_id')->constrained('replies')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qoute_replies');
    }
};
