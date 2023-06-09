<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_partners', function (Blueprint $table) {
            $table->id();
            $table->string('code_product_partner')->nullable();
            $table->string('name_product_partner')->nullable();
            $table->string('price_product_partner')->nullable();
            $table->string('url_product_partner')->nullable();
            $table->string('category_id')->nullable();
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
        Schema::dropIfExists('product_partners');
    }
}
