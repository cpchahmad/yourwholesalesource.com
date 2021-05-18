<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantCsvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_csvs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('variant_sku')->nullable();
            $table->string('ads_price')->nullable();
            $table->string('suggested_resale')->nullable();
            $table->string('compare_at_price')->nullable();
            $table->string('handle')->nullable();
            $table->longText('body_html')->nullable();
            $table->string('option1_name')->nullable();
            $table->string('option1_value')->nullable();
            $table->string('option2_name')->nullable();
            $table->string('option2_value')->nullable();
            $table->string('option3_name')->nullable();
            $table->string('option3_value')->nullable();
            $table->string('title')->nullable();
            $table->string('variant_grams')->nullable();
            $table->string('variant_quantity')->nullable();
            $table->string('variant_barcode')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('variant_weight_unit')->nullable();
            $table->string('cost_per_item')->nullable();
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
        Schema::dropIfExists('variant_csvs');
    }
}
