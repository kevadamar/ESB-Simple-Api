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
        Schema::create('t_billing_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("t_billing_invoice_id");
            $table->bigInteger("m_service_id");
            $table->integer("quantity");
            $table->float("total_subamount");
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
        Schema::dropIfExists('t_billing_invoice_details');
    }
};
