<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnToAdminSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->text('paypal_script_tag')->nullable();
            $table->string('omni_key')->nullable();
            $table->string('usps_user_id')->nullable();
            $table->string('usps_origin_zip')->nullable();
            $table->string('ship_station_key')->nullable();
            $table->string('inflow_api_key')->nullable();
            $table->string('inflow_company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            //
        });
    }
}
