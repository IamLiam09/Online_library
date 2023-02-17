<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThemeNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_theme_setting', function (Blueprint $table) {
            $table->string('theme_name')->nullable()->after('store_id');
            $table->string('type')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_theme_setting', function (Blueprint $table) {
            $table->dropColumn('theme_name');
            $table->dropColumn('type');
        });
    }
}
