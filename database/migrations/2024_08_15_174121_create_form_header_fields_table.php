<?php

use App\Models\Form;
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
        Schema::create('form_header_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Form::class, 'form_id')->constrained('forms')->cascadeOnDelete();
            $table->string('name');
            $table->string('label');
            $table->string('type');
            $table->string('variable_name');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_enabled')->default(false);
            $table->integer('assigned_column')->default(1);
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
        Schema::dropIfExists('form_header_fields');
    }
};
