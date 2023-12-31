<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductTable extends Migration
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
            $table->integer('CategoryId')->foreignIdFor(ProductCategory::class,'id')->constrained()->onDelete('RESTRICT')->onUpdate('RESTRICT');;;
            $table->string('name');
            $table->string('code')->default('Default');
            $table->text('description');
            $table->string('image_path',256);

            $table->double('price'); //Currency Problem
            $table->integer('quantity');

            $table->integer('stars')->default(5);
            $table->integer('reviews')->default(1);

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
        Schema::dropIfExists('products');
    }
}
