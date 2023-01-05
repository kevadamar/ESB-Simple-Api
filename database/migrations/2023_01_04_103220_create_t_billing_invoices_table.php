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
        Schema::create('t_billing_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("from_destination_id");
            $table->bigInteger("to_destination_id");
            $table->date("date_issued")->nullable();
            $table->date("due_date")->nullable();
            $table->string("invoice_no");
            $table->string("subject");
            $table->float("percent_tax");
            $table->float("total_tax");
            $table->float("total_before_tax");
            $table->float("total_after_tax");
            $table->float("total_payment")->default(0);
            $table->boolean("flag_paid")->default(false);
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
        Schema::dropIfExists('t_billing_invoices');
    }
};
