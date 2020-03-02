<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredientWriteofTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_writeof', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->integer('writeof_id')->unsigned()->nullable();
            $table->foreign('writeof_id')->references('id')
                ->on('writeofs')->onDelete('cascade');

            $table->integer('ingredient_id')->unsigned()->nullable();
            $table->foreign('ingredient_id')->references('id')
                ->on('ingredients')->onDelete('cascade');

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
        Schema::table('ingredient_writeof', function (Blueprint $table) {
            //
        });
    }
}
