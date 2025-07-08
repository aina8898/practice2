<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product; 
use App\Models\Sale;

class SalesController extends Controller
{
    public function purchase(Request $request)
{
    $productId = $request->input('product_id'); 
    $quantity = $request->input('quantity', 1);

    $product = Product::find($productId); 

    if (!$product) {
        return response()->json(['message' => config('message.errors.product_not_found')], 404);
    }
    if ($product->stock < $quantity) {
        return response()->json(['message' => config('message.errors.out_of_stock')], 400);
    }

    DB::beginTransaction();

    try {
        // 在庫を減らす
        $product->stock -= $quantity;
        $product->save();

        // 購入履歴を記録
        $sale = new Sale([
            'product_id' => $productId,
        ]);
         $sale->save();

        DB::commit(); // 正常に完了したらコミット

        return response()->json([
                'message' => config('message.success.purchased')
        ]);

    } catch (\Exception $e) {
         DB::rollBack(); // エラーが出たらロールバック

        return response()->json([
            'message' => config('message.errors.purchase_failed'),
            'error' => $e->getMessage() 
        ], 500);
    }
 }
