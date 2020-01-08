<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('act_stock', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('act_id')->unsigned()->nullable();
            $table->foreign('act_id')->references('id')
                ->on('acts')->onDelete('cascade');

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
        Schema::dropIfExists('act_stock');
    }
}
