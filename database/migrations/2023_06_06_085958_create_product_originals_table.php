<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOriginalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_originals', function (Blueprint $table) {
            $table->id();
            $table->string('code_product_original');
            $table->string('name_product_original');
            $table->string('price_product_original');
            $table->string('url_product_original');
            $table->string('catalog_product_original_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_originals');
    }
}
