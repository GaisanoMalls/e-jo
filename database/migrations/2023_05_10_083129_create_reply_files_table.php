<?php

use App\Models\Reply;
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
        Schema::create('reply_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Reply::class, 'reply_id')->constrained();
            $table->string('file_attachment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reply_files');
    }
};