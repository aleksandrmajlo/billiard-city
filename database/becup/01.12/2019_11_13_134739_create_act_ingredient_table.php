<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('act_ingredient', function (Blueprint $table) {

            $table->integer('act_id')->unsigned()->nullable();
            $table->foreign('act_id')->references('id')
                ->on('acts')->onDelete('cascade');

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
        Schema::dropIfExists('act_ingredient');
    }
}
