<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->integer('phone');
            $table->string('company')->nullable();
            $table->integer('country_id');
            $table->integer('city_id');
            $table->integer('zip')->nullable();
            $table->string('notes')->nullable();
            $table->integer('subtotal');
            $table->integer('discount')->nullable();
            $table->integer('charge')->nullable();
            $table->integer('payment_method');
            $table->integer('customer_id');
            $table->integer('total');
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
        Schema::dropIfExists('stripe_orders');
    }
};
