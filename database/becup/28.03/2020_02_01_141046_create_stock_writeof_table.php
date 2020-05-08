<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockWriteofTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_writeof', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('writeof_id')->unsigned()->nullable();
            $table->foreign('writeof_id')->references('id')
                ->on('writeofs')->onDelete('cascade');

            $table->integer('stock_id')->unsigned()->nullable();
            $table->foreign('stock_id')->references('id')
                ->on('stocks')->onDelete('cascade');

            $table->unsignedBigInteger('count')->default(0);
            $table->text('cause')->nullable();

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

        Schema::dropIfExists('stock_writeof');

    }
}
