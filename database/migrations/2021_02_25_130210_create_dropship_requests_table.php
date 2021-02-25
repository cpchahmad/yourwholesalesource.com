<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropshipRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name')->nullable();
            $table->string('packing_size')->nullable();
            $table->double('cost')->nullable();
            $table->double('weight')->nullable();
            $table->double('weekly_sales')->nullable();
            $table->text('description')->nullable();
            $table->string('product_url')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->text('reject_reason')->nullable();
            $table->double('approved_price')->nullable();
            $table->unsignedBigInteger('related_product_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->boolean('battery')->nullable();
            $table->boolean('relabell')->nullable();
            $table->boolean('re_pack')->nullable();
            $table->boolean('imported_to_store')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('option_count')->nullable();
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
        Schema::dropIfExists('dropship_requests');
    }
}
