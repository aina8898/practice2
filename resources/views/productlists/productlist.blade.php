@extends('layouts.app')
@section('title', '商品一覧')

@section('content')

<div class="container list-container">

    <h2>商品一覧画面</h2>

    <form action="{{ route('products.index') }}" method="GET" class="searchbox">

        @csrf

        <input type="text" name="keyword" class="keyword" placeholder="検索キーワード" value="{{ request('keyword') }}">

        <select name="company" data-toggle="select">
            <option value="">メーカー名</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" >{{ $company->company_name }}</option>
            @endforeach
        </select>
        
        <input type="submit" value="検索" class="submitbtn">

    </form>

    <table class = "table table-striped list-table">
        <thead>
            <tr>
                <th class="num">ID</th>
                <th class="img">商品画像</th>
                <th class="name">商品名</th>
                <th class="price">価格</th>
                <th class="stock">在庫数</th>
                <th class="company">メーカー名</th>
                <th><button type="button" class="create-btn"  onclick="location.href='{{ route('products.create') }}'">新規登録</button></th>
            </tr>
        </thead>

        <tbody>
                
            @foreach($products as $product)

            <tr>
                <td>{{ $product->id }}</td>
                <td><img src="{{ asset($product->image) }}" ></td>
                <td>{{ $product->product_name }}</td>
                <td>¥{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->company->company_name }}</td>
                <td class="td-btn">
                    <button type="button" class="edit-btn" onclick="location.href='{{ route('products.show', $product) }}' ">詳細表示</button>
        
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">削除</button>
                    </form>
                </td>
            </tr>

            @endforeach
        </tbody>

    </table>

    <div class="page">{{ $products->appends(request()->query())->links() }}</div>

</div>
@endsection
