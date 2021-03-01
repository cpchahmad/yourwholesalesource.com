<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_marks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dropship_request_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('option')->nullable();
            $table->integer('inventory')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('shipping_marks');
    }
}
