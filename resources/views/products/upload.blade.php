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
                    
                    <div class="form-group" align="center">
                        {!! Form::label('infomation', '既に登録されている在庫を更新したい場合には下記の検索フォームで検索') !!}
                    </div>

                    <div class="form-group">
                        <div id="search-area">
                            {{ Form::open(['method' => 'GET', 'url' => route('product.search')] ) }}
                            {{ Form::select('category_id', $categoryList, null, ['id' => 'category_id', 'placeholder' => 'すべてのカテゴリ']) }}
                            {{ Form::text('search_text', null, ['id' => 'search_text']) }}
                            <button type="submit" class="btn btn-success" id="search_Button">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            
                            {{ Form::close() }}
                        </div>
                        
                        @if (!empty($products))
                        <div id="content_wrapper">
                            <div class="form-group" align="center">
                                {!! Form::label('infomation', '下記のサムネイルをクリックすると変更画面に遷移します') !!}
                            </div>
                            @foreach ($products->chunk(4) as $key => $product)
                            <ul id="item_content">
                                <li>
                                    <a href="{{ route('edit', $products[$key]->id) }}">
                                        <img src="{{ asset('storage/image/' . $products[$key]->product_image) }}" alt="image" value="{{ $products[$key]->product_image }}" />
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('edit', $products[$key + 1]->id) }}">
                                        <img src="{{ asset('storage/image/' . $products[$key + 1]->product_image) }}" alt="image" value="{{ $products[$key + 1]->product_image }}" />
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('edit', $products[$key + 2]->id) }}">
                                        <img src="{{ asset('storage/image/' . $products[$key + 2]->product_image) }}" alt="image" value="{{ $products[$key + 2]->product_image }}" />
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('edit', $products[$key + 3]->id) }}">
                                        <img src="{{ asset('storage/image/' . $products[$key + 3]->product_image) }}" alt="image" value="{{ $products[$key + 3]->product_image }}" />
                                    </a>
                                </li>
                            </ul>
                            @endforeach
                        </div>
                            
                        <div class="pagination-bar text-center">
                            {{ $products->appends(Request::only('category_id', 'search_text'))->links() }}
                        </div>
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