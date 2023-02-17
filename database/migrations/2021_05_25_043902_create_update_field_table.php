<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'user_details', function (Blueprint $table){
            $table->string('billing_postalcode')->change();
            $table->string('shipping_address')->nullable()->change();
            $table->string('shipping_country')->nullable()->change();
            $table->string('shipping_city')->nullable()->change();
            $table->string('shipping_postalcode')->nullable()->change();
        }
        );
        Schema::table(
            'stores', function (Blueprint $table){
            $table->string('whatsapp')->nullable()->change();
            $table->string('facebook')->nullable()->change();
            $table->string('instagram')->nullable()->change();
            $table->string('twitter')->nullable()->change();
            $table->string('youtube')->nullable()->change();
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

    }
}
