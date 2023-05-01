<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Satış oluştur
        $newSale = new Sale();
        $newSale->save();
        $saleId = $newSale->id;


        $sale = Sale::find($saleId);
        if (!$sale) {
            return response()->json(['error' => 'Sale not found.'], 404);
        }

        $basketItems = (array) $request->input('basket_items');
        foreach ($basketItems as $item) {
            // Sepet kaydı oluştur
            $basket = new Basket();
            $basket->sale_id = $saleId;
            $basket->product_id = $item['product_id'];
            $basket->product_price = $item['product_price'];
            $basket->piece = $item['piece'];
            $basket->total_price = $basket->product_price * $basket->piece;
            $basket->save();

            // Ürün stoklarını güncelle
           // $product = Product::findOrFail($basket->product_id);
                Stock::where('product_id',  $item['product_id'])
                ->where('stock_price',$item['product_price'])
                ->decrement('quantity',$item['piece']);
                Product::where('id',$item['product_id'])
                ->decrement('stock_quantity',$item['piece']);
             $sale->total_price += $basket->total_price;
        }

        $sale->save();

        // Cevap döndür
        return response()->json([
            'sale' => $sale,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
