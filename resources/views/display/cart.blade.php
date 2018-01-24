@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Shopping Cart</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productData as $product)
                                    <tr>
                                        <th>
                                            <img src="{{ asset('storage/image/' . $product->product_image) }}" alt="image" width=300 height=300 />
                                        </th>
                                        <th>{{ $product->product_name }}</th>
                                        <th>{{ $product->price }}円</th>
                                        <th>
                                            {!! Form::open() !!}
                                                <input type=button value="-" onClick="plus_or_minus(this.form.product_purchased, {{ $product->stock }} , '-');">
                                                <input type=text name="product_purchased" value="{{ $product->added }}" size=4 readonly="readonly">
                                                <input type=button value="＋" onClick="plus_or_minus(this.form.product_purchased, {{ $product->stock }} , '+');">
                                            {!! Form::close() !!}
                                        </th>
                                        <th>
                                            <form action="{{ url('/display/emptyCart/'.$product->product_id) }}" method="POST" class="form-horizontal">
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-info">
                                                    <i class="fa fa-shopping-cart"></i>カートから削除
                                                </button>
                                            </form>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="form-group">
                        @if(!empty($productData))
                        <a href="{{ route('checkout') }}">
                            レジに進む
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        @else
                        <a href="{{ route('checkout') }}" class="disabled" tabindex="-1">
                            レジに進む
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        @endif
                        
                        <a href="{{ route('display') }}">
                            商品一覧へ戻る
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection