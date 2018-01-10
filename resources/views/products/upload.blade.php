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
                        {!! Form::file('file') !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('productName', '商品名:') !!}
                        {!! Form::text('productName', null, ['class' => 'form-control']) !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('price', '販売金額:') !!}
                        {!! Form::text('price', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('アップロード', ['class' => 'btn btn-default']) !!}
                    </div>
                    
                    <div class="form-group">
                        @if ($products)
                            @foreach ($products as $product)
                                <div style="float:left">
                                    <img src="{{ asset('storage/image/' . $product->product_image) }}" alt="image" width=100 height=100 />
                                </div>
                            @endforeach
                        @endif
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection