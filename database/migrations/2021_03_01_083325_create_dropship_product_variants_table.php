<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropshipProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dropship_product_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('option')->nullable();
            $table->integer('inventory')->nullable();
            $table->string('image')->nullable();
            $table->string('barcode')->nullable();
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
        Schema::dropIfExists('dropship_product_variants');
    }
}
