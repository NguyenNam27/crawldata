<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code_product')->nullable();
            $table->string('name')->index()->nullable();
            $table->string('category_id')->nullable();
            $table->string('price_cost')->nullable();
            $table->string('price_partner')->nullable();
            $table->string('result')->nullable();
            $table->string('status')->default(1);
            $table->string('link_product')->nullable();
            $table->timestamps();
//            $table->foreign('category_id')->references('id')->on('categories')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
