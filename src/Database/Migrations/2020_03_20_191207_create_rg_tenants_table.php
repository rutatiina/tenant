<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRgTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rg_tenants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            //>> default columns
            $table->softDeletes();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            //<< default columns

            //>> table columns
            $table->string('type')->nullable();
            $table->string('database')->nullable();
            $table->string('partitioning')->nullable();
            $table->string('service_id')->nullable();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('alias')->nullable();
            $table->string('email')->nullable();
            $table->string('industry')->nullable();
            $table->string('country')->nullable();
            $table->string('street_line_1')->nullable();
            $table->string('street_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state_province')->nullable();
            $table->string('zip_postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('base_currency')->nullable();
            $table->string('fiscal_year')->nullable();
            $table->string('fiscal_year_start')->nullable();
            $table->string('language')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('date_format')->nullable();
            $table->string('company_id_name')->nullable();
            $table->string('company_id_value')->nullable();
            $table->string('tax_id_name')->nullable();
            $table->string('tax_id_value')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending','suspended'])->nullable();
            $table->string('sms_credits')->nullable();
            $table->enum('inventory_valuation_system', ['perpetual', 'periodic'])->nullable();
            $table->enum('inventory_valuation_method', ['fifo', 'lifo', 'avco', 'auco'])->nullable();
            $table->unsignedTinyInteger('decimal_places')->nullable()->default(2);
            $table->string('package_accounts')->nullable();
            $table->string('package_human_resource')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rg_tenants');
    }
}
