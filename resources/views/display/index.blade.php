@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Display Screen</div>
                <div class="panel-body">
                    {{ Form::label('search', 'Search Form') }}
                    <div id="search-area">
                        {{ Form::open(['method' => 'GET', 'url' => route('display.search')] ) }}
                        {{ Form::select('category_id', $categoryList, null, ['id' => 'category_id', 'placeholder' => 'すべてのカテゴリ']) }}
                        {{ Form::text('search_text', null, ['id' => 'search_text']) }}
                        <button type="submit" class="btn btn-success" id="search_Button">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                        
                        {{ Form::close() }}
                    </div>
                    
                    <div id="content_wrapper">
                        @foreach ($productInfo->chunk(4) as $key => $info)
                        <ul id="item_content">
                            <li>
                                <div class="product_data">
                                    <div class="image_file">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key]->id) }}">
                                            <p>
                                                <img src="{{ asset('storage/image/' . $productInfo[$key]->product_image) }}" alt="image" />
                                            </p>
                                        </a>
                                    </div>
                                    <div class="product_name">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key]->id) }}">
                                            {{ $productInfo[$key]->product_name }}
                                        </a>
                                    </div>
                                    <div class="product_price">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key]->id) }}">
                                            <span class="a-size-base a-color-price s-price a-text-bold">
                                                ￥ {{ $productInfo[$key]->price }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="product_data">
                                    <div class="image_file">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 1]->id) }}">
                                            <p>
                                                <img src="{{ asset('storage/image/' . $productInfo[$key + 1]->product_image) }}" alt="image" />
                                            </p>
                                        </a>
                                    </div>
                                    <div class="product_name">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 1]->id) }}">
                                            {{ $productInfo[$key + 1]->product_name }}
                                        </a>
                                    </div>
                                    <div class="product_price">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 1]->id) }}">
                                            <span class="a-size-base a-color-price s-price a-text-bold">
                                                ￥ {{ $productInfo[$key + 1]->price }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="product_data">
                                    <div class="image_file">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 2]->id) }}">
                                            <p>
                                                <img src="{{ asset('storage/image/' . $productInfo[$key + 2]->product_image) }}" alt="image" />
                                            </p>
                                        </a>
                                    </div>
                                    <div class="product_name">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 2]->id) }}">
                                            {{ $productInfo[$key + 2]->product_name }}
                                        </a>
                                    </div>
                                    <div class="product_price">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 2]->id) }}">
                                            <span class="a-size-base a-color-price s-price a-text-bold">
                                                ￥ {{ $productInfo[$key + 2]->price }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="product_data">
                                    <div class="image_file">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 3]->id) }}">
                                            <p>
                                                <img src="{{ asset('storage/image/' . $productInfo[$key + 3]->product_image) }}" alt="image" />
                                            </p>
                                        </a>
                                    </div>
                                    <div class="product_name">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 3]->id) }}">
                                            {{ $productInfo[$key + 3]->product_name }}
                                        </a>
                                    </div>
                                    <div class="product_price">
                                        <a href="{{ url('/display/detail/'.$productInfo[$key + 3]->id) }}">
                                            <span class="a-size-base a-color-price s-price a-text-bold">
                                                ￥ {{ $productInfo[$key + 3]->price }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        @endforeach
                    </div>
                    
                    @if($productInfo)
                        <div class="pagination-bar text-center">
                               {{ $productInfo->appends(Request::only('category_id', 'search_text'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection