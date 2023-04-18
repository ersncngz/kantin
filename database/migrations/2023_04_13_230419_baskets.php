<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_id');
            $table->foreign('sale_id')->references('id')->on('sales'); 
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products'); 
            $table->integer('product_price');
            $table->integer('piece');
            $table->integer('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baskets');
    }
};
