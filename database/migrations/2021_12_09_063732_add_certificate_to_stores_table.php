<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCertificateToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('certificate_template')->nullable()->after('currency_symbol_space');
            $table->string('certificate_color')->nullable()->after('currency_symbol_space');
            $table->string('certificate_gradiant')->nullable()->after('currency_symbol_space');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('certificate_template');
            $table->dropColumn('certificate_color');
            $table->dropColumn('certificate_gradiant');
        });
    }
}
