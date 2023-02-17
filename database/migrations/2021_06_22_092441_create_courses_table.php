<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('title');
            $table->string('type');
            $table->text('course_requirements');
            $table->text('course_description');
            $table->string('has_certificate');
            $table->text('status');
            $table->string('category')->nullable();
            $table->string('quiz')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('level');
            $table->string('lang')->default('en');
            $table->string('duration')->nullable();
            $table->string('is_free')->nullable();
            $table->string('price')->nullable();
            $table->string('has_discount')->nullable();
            $table->string('discount')->nullable();
            $table->string('featured_course');
            $table->string('is_preview');
            $table->string('preview_type')->nullable();
            $table->string('preview_content')->nullable();
            $table->string('host')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
