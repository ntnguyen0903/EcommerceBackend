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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name_product',32)->unique();
            $table->float('price');
            $table->float('price_off');
            $table->string('img');
            $table->string('url',200);
            $table->string('monitor',200);
            $table->string('detail');
            $table->double('quantity');
            $table->string('ram',50);
            $table->string('hard_drive',50);
            $table->string('os',50);
            $table->timestamps(); 

            $table->unsignedBigInteger('cat_id');
            $table->foreign('cat_id')->references('id')->on('category');

            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
};
