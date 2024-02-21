@extends('layouts.app')
@section('title', '商品新規登録')

@section('content')

<div class="container">

    <h2>商品新規登録</h2>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="createpage">

        @csrf

        <div>
            <label for="product_name" class="form-label">商品名<span class="required">※</span></label>
            <input id="product_name" type="text" name="product_name" class="form-control" required>
        </div>

        <div>
            <label for="company_id" class="form-label">メーカー名<span class="required">※</span></label>
            <select class="form-select" id="company_id" name="company_id">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="price" class="form-label">価格<span class="required">※</span></label>
            <input id="price" type="text" name="price" class="form-control" required>
        </div>

        <div>
            <label for="stock" class="form-label">在庫数<span class="required">※</span></label>
            <input id="stock" type="text" name="stock" class="form-control" required>
        </div>

        <div>
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control"></textarea>
        </div>

        <div>
            <label for="image" class="form-label">商品画像</label>
            <input id="image" type="file" name="image" class="form-control">
        </div>

        <div class="createpage-btn">
            <button type="submit" class="create-btn">新規登録</button>
            <button type="button" onclick= "location.href='{{ route('products.index') }}' ">戻る</button>
        </div>
    
    </form> 
    
</div>
@endsection