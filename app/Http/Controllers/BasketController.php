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
        $updatedStocks = [];
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
            $product = Product::findOrFail($basket->product_id);
            $stocks = Stock::where('product_id', $product->id)
                ->orderBy('stock_price', 'asc')
                ->orderBy('invoice_date', 'asc')
                ->get();
            $stock_quantity = $basket->piece;

            foreach ($stocks as $key => $stock) {
                while ($stock_quantity > 0 && $stock) {
                    if ($stock->quantity >= $stock_quantity) {
                        $stock->quantity -= $stock_quantity;
                        $stock_quantity = 0;
                    } else {
                        $stock_quantity -= $stock->quantity;
                        $stock->quantity = 0;
                    }

                    $stock->save();
                    $updatedStocks[] = $stock;

                    $product->stock_quantity -= $stock_quantity * $stock->price;

                    // Bir sonraki stok kaydını al
                    $stock = $stocks->get($key + 1);
                }

                if ($stock_quantity <= 0) {
                    break;
                }

                $stock->save();
                $updatedStocks[] = $stock;

                $product->stock -= $stock_quantity * $stock->price;
            }

            $product->save();

            $product->stock_quantity -= $basket->piece;
            $product->save();
            // Satış toplam tutarını güncelle
            $sale->total_price += $basket->total_price;
        }

        $sale->save();

        // Cevap döndür
        return response()->json([
            'sale' => $sale,
            'basket' => $basket
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
