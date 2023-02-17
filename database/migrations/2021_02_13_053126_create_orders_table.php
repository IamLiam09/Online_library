<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'orders', function (Blueprint $table){
            $table->id();
            $table->string('order_id', 100);
            $table->string('name', 100)->nullable();
            $table->string('card_number', 10)->nullable();
            $table->string('card_exp_month', 10)->nullable();
            $table->string('card_exp_year', 10)->nullable();
            $table->integer('student_id')->nullable();
            $table->text('course')->nullable();
            $table->float('price');
            $table->string('plan_name')->nullable();
            $table->string('plan_id')->nullable();
            $table->string('price_currency', 10);
            $table->string('txn_id', 100);
            $table->string('payment_type', 100);
            $table->string('payment_status', 100);
            $table->string('receipt')->nullable();
            $table->integer('store_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('subscription_id', 100)->nullable();
            $table->string('payer_id', 100)->nullable();
            $table->string('payment_frequency', 100)->nullable();
            $table->integer('created_by')->default('0');
            $table->timestamps();
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
        Schema::dropIfExists('orders');
    }
}
