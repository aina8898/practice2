<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::query();
        $companies = Company::all();
        
        $keyword = $request->input('keyword');
        $company = $request->input('company');

        if (!empty($keyword)) {
            $products->where('product_name', 'LIKE', "%{$keyword}%");
        }

        if (!empty($company)) {
            $products->where('company_id', 'LIKE', "%{$company}%");
        }

        if($min_price = $request->min_price){
            $products->where('price', '>=', $min_price);
        }

        if($max_price = $request->max_price){
            $products->where('price', '<=', $max_price);
        }

        if($min_stock = $request->min_stock){
            $products->where('stock', '>=', $min_stock);
        }

        if($max_stock = $request->max_stock){
            $products->where('stock', '<=', $max_stock);
        }

        if($sort = $request->sort){
            $direction = $request->direction == 'desc' ? 'desc' : 'asc'; 
            $products->orderBy($sort, $direction);
        }

        $products = $products->paginate(3)->appends($request->all());

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
        $request->validate([
            'product_name' => 'required', 
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable', 
            'image' => 'nullable|image|max:2048',
        ]);

        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
        ]);

        if  ($request->hasFile('image')){ 
            $filename = $request->image->getClientOriginalName();
            $filePath = $request->image->storeAs('products', $filename, 'public');
            $product->image = '/storage/' . $filePath;
        }

        $product->save();
        return redirect('products');
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
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable', 
            'image' => 'nullable|image|max:2048',
        ]);

        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;

        if ($request->hasFile('image')){ 
            $filename = $request->image->getClientOriginalName();
            $filePath = $request->image->storeAs('products', $filename, 'public');
            $product->image = '/storage/' . $filePath;
        }

        $product->save();

        return redirect()->route('products.index')
            ->with('success', '商品情報を編集しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Product $product)
    {
        $product->delete();
        return redirect('/products');
    }
}
