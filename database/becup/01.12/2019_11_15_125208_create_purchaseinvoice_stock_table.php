<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseinvoiceStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseinvoice_stock', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->integer('purchaseinvoice_id')->unsigned()->nullable();

            $table->foreign('purchaseinvoice_id')->references('id')
                ->on('purchaseinvoices')->onDelete('cascade');

            $table->integer('stock_id')->unsigned()->nullable();
            $table->foreign('stock_id')->references('id')
                ->on('stocks')->onDelete('cascade');

            $table->unsignedBigInteger('count')->default(0);

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
        Schema::dropIfExists('purchaseinvoice_stock');
    }
}
