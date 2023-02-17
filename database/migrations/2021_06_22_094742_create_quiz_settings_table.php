<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('quiz_group');
            $table->string('min_percentage');
            $table->string('category');
            $table->string('sub_category');
            $table->text('instructions');
            $table->string('time');
            $table->string('total_time')->nullable();
            $table->string('per_question_time')->nullable();
            $table->string('review')->nullable();
            $table->string('result_after_submit')->nullable();
            $table->string('random_questions')->nullable();
            $table->string('created_by');
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
        Schema::dropIfExists('quiz_settings');
    }
}
