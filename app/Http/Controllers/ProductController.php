<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string|max:50',
        ]);

        $filters = $request->only([
         'keyword', 'company', 
         'min_price', 'max_price', 
         'min_stock', 'max_stock', 
         'sort', 'direction'
        ]);

        $products = Product::searchProducts($filters)->appends($request->all());
        $companies = Company::all();

        return view('productlists.productlist', compact('products', 'companies'));
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('productlists.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:100',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            Product::createProduct($validatedData);
            DB::commit();
            return redirect('products')->with('success', config('message.success.saved'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('商品登録エラー: ' . $e->getMessage());
            return back()->with('error', config('message.errors.save_failed'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('productlists.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $companies = Company::all();

        return view('productlists.edit', compact('product', 'companies'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:100',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable|string|max:255', 
            'image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->comment = $request->comment;
    
            if ($request->hasFile('image')) {
                $filename = $request->image->getClientOriginalName();
                $filePath = $request->image->storeAs('products', $filename, 'public');
                $product->image = '/storage/' . $filePath;
            }

            $product->save();

            DB::commit();
            return redirect()->route('products.index')->with('success', config('message.success.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('商品更新エラー: ' . $e->getMessage());
            return back()->with('error', config('message.errors.update_failed'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            return redirect('/products')->with('success', config('message.success.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('商品削除エラー: ' . $e->getMessage());
            return back()->with('error', config('message.errors.delete_failed'));
        }
    }

}