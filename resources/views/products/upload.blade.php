@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Upload Files</div>

                <div class="panel-body">
                    {!! Form::open(['url' => '/product/upload', 'method' => 'post', 'files' => true]) !!}

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
                        {!! Form::label('file', '画像アップロード', ['class' => 'control-label']) !!}
                        {!! Form::file('file[]', array('multiple' => true)) !!}
                    </div>
                    
                    <div class="add_button_area"></div>
                    <button type="button" class="btn btn-default" onclick="addUpload();">アップロード欄を追加</button>
                    
                    <div class="form-group">
                        {!! Form::label('productName', '商品名:') !!}
                        {!! Form::text('productName', null, ['class' => 'form-control']) !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('category_id', 'カテゴリ選択:') !!}
                        {{ Form::select('category_id', $categoryList) }}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('price', '販売金額:') !!}
                        {!! Form::text('price', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('stock', '個数:') !!}
                        {!! Form::text('stock', null, ['class' => 'form-control']) !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::submit('アップロード', ['class' => 'btn btn-default']) !!}
                    </div>
                    {!! Form::close() !!}
                    <div class="form-group">
                        @if ($products)
                            @foreach ($products as $product)
                                <div class="imageArea" style="float:left">
                                    <a href="#" onclick="imgClick();">
                                        <img src="{{ asset('storage/image/' . $product->product_image) }}" alt="image" width=100 height=100 value="{{ $product->product_image }}" />
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                <div class="form-group">                           
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