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
    $barcode_no = $request->input('barcode_no');
    $products = DB::table('products')
                ->join('sales', 'products.id','=','sales.product_id')
                ->select('products.*','sales.*')
                ->where('barcode_no', '=', $barcode_no)
                ->get();
                
                return response()->json($products);
    }

     public function index()
    {
        return response()->json([
            "status" => "success",
            "data" => Product::all()
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
        
        $barcode = $request->input('barcode_no');
        $products = DB::table('products')
                     ->join('sales', 'products.id','=','sales.product_id')
                     ->select('products.*','sales.*')
                     ->where('products.barcode_no', '=', $barcode)
                     ->get();
                     
        
        $validator = Validator::make($request->all(), [
            'barcode_no' => 'required',
            'product_name' => 'required',
            'sale_price' => 'required',
            'stock_quantity' => 'required',
            'invoice_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => "warning",
                "message" => $validator->errors()
            ]);
        }

       $product = Product::create($request->all());
        return response()->json([
            "status" => "success"
        ], 201);
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
            'barcode_no' => 'required',
            'product_name' => 'required',
            'sale_price' => 'required',
            'stock_quantity' => 'required',
           
        ]);
        $product->update($request->all());
        return response()->json([
            "status" => "success"
        ], 200);


        if ($validator->fails()) {
            return response()->json([
                "status" => "warning",
                "message" => $validator->errors()
            ]);
        
}        
        if (!$product) {
            return response()->json([
                "status" => "warning",
                "message" => "Ürün bulunamadı"
            ], 404);
    }
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
