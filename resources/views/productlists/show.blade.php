@extends('layouts.app')
@section('title', '商品情報詳細')

@section('content')

<div class="container">

    <h2>商品情報詳細画面</h2>

    @csrf
    @method('PUT')

    <div class ="showpage">

        <dl class="showproduct">

            <dt>ID.</dt>
            <dd>{{ $product->id }}</dd>

            <dt>商品画像</dt>
            <dd><img src="{{ asset($product->image) }}"  class="product-img"></dd>

            <dt>商品名</dt>
            <dd>{{ $product->product_name }}</dd>

            <dt>メーカー</dt>
            <dd>{{ $product->company->company_name }}</dd>

            <dt>価格</dt>
            <dd>{{ $product->price }}</dd>

            <dt>在庫数</dt>
            <dd>{{ $product->stock }}</dd>

            <dt>コメント</dt>
            <dd class="text">{{ $product->comment }}</dd>

        </dl>   

        <div class="showpage-btn">
            <button type="button" class="edit-btn"  onclick="location.href='{{ route('products.edit',$product) }}'">編集</button>
            <button type="button" onclick= "location.href='{{ route('products.index',$product) }}' ">戻る</button>
        </div>

    </div>
    
</div>
@endsection