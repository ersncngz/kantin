<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); 
            $table->bigInteger('list_sales_id')->unsigned();
            $table->foreign('list_sales_id')->references('id')->on('list_sales');
            $table->integer('piece');
            $table->integer('basket_price');
            $table->integer('total_price');
            $table->timestamps();*/ 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');; 
            $table->integer('quantity')->default(0)->nullable();  
            $table->decimal('stock_price')->default(0);
            $table->timestamp('invoice_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
