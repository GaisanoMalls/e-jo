<?php

use App\Models\ServiceDepartment;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ServiceDepartment::class, 'service_department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Team::class, 'team_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('description');
            $table->boolean('is_important')->default(false);
            $table->boolean('is_draft')->default(false);
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
        Schema::dropIfExists('announcements');
    }
}