<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCsvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_csvs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('handle')->nullable();
            $table->string('title')->nullable();
            $table->string('vendor')->nullable();
            $table->string('type')->nullable();
            $table->string('tags')->nullable();
            $table->string('published')->nullable();
            $table->string('option1_name')->nullable();
            $table->string('option1_value')->nullable();
            $table->string('option2_name')->nullable();
            $table->string('option2_value')->nullable();
            $table->string('option3_name')->nullable();
            $table->string('option3_value')->nullable();
            $table->string('variant_sku')->nullable();
            $table->string('variant_price')->nullable();
            $table->string('variant_compare_at_price')->nullable();
            $table->string('variant_grams')->nullable();
            $table->string('variant_quantity')->nullable();
            $table->string('variant_barcode')->nullable();
            $table->string('image_src')->nullable();
            $table->string('image_position')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('variant_weight_unit')->nullable();
            $table->string('variant_image')->nullable();
            $table->string('cost_per_item')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('product_csvs');
    }
}
