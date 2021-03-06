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
                    
                    @if ($rankingProductData)
                    <div id="popular">
                        <h3 align="center">人気の商品</h3>
                            <ul id="item_content">
                            @foreach ($rankingProductData as $info)
                                <li>
                                    <div class="product_data">
                                        <div class="image_file">
                                            <a href="{{ url('/display/detail/'.$info->id) }}">
                                                <p>
                                                    <img src="{{ asset('storage/image/' . $info->product_image) }}" alt="image" />
                                                </p>
                                            </a>
                                        </div>
                                        <div class="product_name">
                                            <a href="{{ url('/display/detail/'.$info->id) }}">
                                                {{ $info->product_name }}
                                            </a>
                                        </div>
                                        <div class="product_price">
                                            <a href="{{ url('/display/detail/'.$info->id) }}">
                                                <span class="a-size-base a-color-price s-price a-text-bold">
                                                    ￥ {{ $info->price }}
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div id="content_wrapper">
                        @foreach ($productInfo as $key => $info)
                            @if ($key % 4 == 0)
                            <ul id="item_content">
                            @endif
                                <li>
                                    <div class="product_data">
                                        <div class="image_file">
                                            <a href="{{ url('/display/detail/'.$info->id) }}">
                                                <p>
                                                    <img src="{{ asset('storage/image/' . $info->product_image) }}" alt="image" />
                                                </p>
                                            </a>
                                        </div>
                                        <div class="product_name">
                                            <a href="{{ url('/display/detail/'.$info->id) }}">
                                                {{ $info->product_name }}
                                            </a>
                                        </div>
                                        <div class="product_price">
                                            <a href="{{ url('/display/detail/'.$info->id) }}">
                                                <span class="a-size-base a-color-price s-price a-text-bold">
                                                    ￥ {{ $info->price }}
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @if ($key % 4 == 3 || $key == count($productInfo))
                            </ul>
                            @endif
                        @endforeach
                    </div>
                    
                    @if($productInfo)
                        <div class="pagination-bar text-center">
                               {{ $productInfo->appends(Request::only('category_id', 'search_text'))->links() }}
                        </div>
                    @endif
                    
                    <div id="recent"></div>
                    <script type="text/javascript">
                        getStorage();
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection