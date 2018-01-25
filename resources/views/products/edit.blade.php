@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Product Edit</div>

                <div class="panel-body">
                    {!! Form::open(['url' => route('edit', $product->id), 'method' => 'post', 'files' => true]) !!}

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>  
                        </div>
                    @endif
                    <div class="form-group">
                        <img src="{{ asset('storage/image/' . $product->product_image) }}" alt="image" width=300 height=300 />
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('productName', '商品名:') !!}
                        {!! Form::text('productName', $product->product_name, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('price', '販売金額:') !!}
                        {!! Form::text('price', $product->price, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('stock', '個数:') !!}
                        {!! Form::text('stock', $product->stock, ['class' => 'form-control']) !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::submit('在庫変更', ['class' => 'btn btn-default']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                
                <div class="form-group">
                    <a href="{{ route('product') }}">
                        商品登録画面へ戻る
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>                           
                    <a href="{{ route('display') }}">
                        商品一覧へ戻る
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection