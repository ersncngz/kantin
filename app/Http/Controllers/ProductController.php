<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function barcode(Request $request)
    {
    
        $barcode_no = $request->input('barcode_no');
        $validatedData = $request->validate([
        'barcode_no' => 'required|string|max:255',
        ]);
        $products = DB::table('products')->where('barcode_no','=',$barcode_no)->get();
    return response()->json([
            
            "data" => $products
        ]);
    
     }

     public function index(Request $request)
    { 
        return response()->json([
            'message'=>'success',
            'data' => Product::all()
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
        $product = new Product;
        $product->barcode_no = $request->input('barcode_no');
        $product->product_name = $request->input('product_name');
        $product->stock_quantity = null; // varsayılan olarak null değer atıyoruz
        $product->invoice_date = $request->input('invoice_date');
        $product->save();
        return response()->json([
            "status" => "success"         
     ]);
            }

    public function show($id)
    {
        return response()->json([
                "status" => "success",
             "data" => Product::findOrFail($id)
         ]);
    }

 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'barcode_no' => 'required|unique:products,barcode_no,'.$product->id,
            'product_name' => 'required',           
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                "status" => "warning",
                "message" => $validator->errors()
            ]);
        }
        
        $product->update($request->all());
        
        return response()->json([
            "status" => "success"
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            "status"=>"success",
        ]);
    }
}
