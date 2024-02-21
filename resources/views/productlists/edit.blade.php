@extends('layouts.app')
@section('title', '商品情報編集')

@section('content')

<div class="container">

    <h2>商品情報編集画面</h2>

    <form method="POST" action="{{ route('products.update',$product) }}" enctype="multipart/form-data" class="editpage">

        @csrf
        @method('PUT')

        <div>ID. {{ $product->id }}</div>

        <div>
            <label for="product_name" class="form-label">商品名<span class="required">※</span></label>
            <input id="product_name" type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
        </div>

        <div>
            <label for="company_id" class="form-label">メーカー名<span class="required">※</span></label>
            <select class="form-select" id="company_id" name="company_id" value="{{ old('company_id', $product->company_id) }}">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="price" class="form-label">価格<span class="required">※</span></label>
            <input id="price" type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        <div>
            <label for="stock" class="form-label">在庫数<span class="required">※</span></label>
            <input id="stock" type="text" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
        </div>

        <div>
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control">{{ old('comment', $product->comment) }}</textarea>
        </div>

        <div>
            <label for="image" class="img-label">商品画像</label>
            <input id="image" type="file" name="image" class="img-form">
            <img src="{{ asset($product->image) }}" alt="商品画像" class="product-img">
        </div>

        <div class="editpage-btn">
            <button type="submit" class="edit-btn">更新</button>
            <button type="button" onclick= "location.href='{{ route('products.index') }}' ">戻る</button>
        </div>
    
    </form> 
    
</div>
@endsection