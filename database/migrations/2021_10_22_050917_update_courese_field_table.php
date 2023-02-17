<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoureseFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'courses', function (Blueprint $table){
            $table->string('title')->nullable()->change();
            $table->string('type')->nullable()->change();
            $table->text('course_requirements')->nullable()->change();
            $table->text('course_description')->nullable()->change();
            $table->string('has_certificate')->nullable()->change();
            $table->text('status')->nullable()->change();
            $table->string('level')->nullable()->change();
            $table->string('featured_course')->nullable()->change();
            $table->string('is_preview')->nullable()->change();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'courses', function (Blueprint $table){
            $table->string('title')->nullable()->change();
            $table->string('type')->nullable()->change();
            $table->text('course_requirements')->nullable()->change();
            $table->text('course_description')->nullable()->change();
            $table->string('has_certificate')->nullable()->change();
            $table->text('status')->nullable()->change();
            $table->string('level')->nullable()->change();
            $table->string('featured_course')->nullable()->change();
            $table->string('is_preview')->nullable()->change();
        }
        );
    }
}
