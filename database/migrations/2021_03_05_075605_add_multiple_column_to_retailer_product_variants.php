<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnToRetailerProductVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retailer_product_variants', function (Blueprint $table) {
            $table->integer('is_dropship_variant')->nullable();
            $table->unsignedBigInteger('linked_variant_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retailer_product_variants', function (Blueprint $table) {
            //
        });
    }
}
