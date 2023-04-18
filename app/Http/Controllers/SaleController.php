<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "status" => "success",
            "data" => Sale::all()
        ]);
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
    $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'piece' => 'required',
                'basket_price' => 'required',
                'total_price' => 'required',
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    "status" => "warning",
                    "message" => $validator->errors()
                ]);
            }

        $product = Sale::create($request->all());
            return response()->json([
                "status" => "success"
            ], 201); 
           }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json([
            "status" => "success",
         "data" => Sale::findOrFail($id)
     ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'piece' => 'required',
            'basket_price' => 'required',
            'total_price' => 'required',
            
        ]);
        $sale->update($request->all());
        return response()->json([
            "status" => "success"
        ], 200);


        if ($validator->fails()) {
            return response()->json([
                "status" => "warning",
                "message" => $validator->errors()
            ]);
        
}        
        if (!$sale) {
            return response()->json([
                "status" => "warning",
                "message" => "Ürün bulunamadı"
            ], 404);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
          $sale->delete();
        return response()->json([
            "status"=>"success",
        ]);
    }
}
