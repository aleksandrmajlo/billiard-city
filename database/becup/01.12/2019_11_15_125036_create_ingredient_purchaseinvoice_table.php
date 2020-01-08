<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredientPurchaseinvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_purchaseinvoice', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->integer('purchaseinvoice_id')->unsigned()->nullable();
            $table->foreign('purchaseinvoice_id')->references('id')
                ->on('purchaseinvoices')->onDelete('cascade');

            $table->integer('ingredient_id')->unsigned()->nullable();
            $table->foreign('ingredient_id')->references('id')
                ->on('ingredients')->onDelete('cascade');

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
        Schema::dropIfExists('ingredient_purchaseinvoice');
    }
}
