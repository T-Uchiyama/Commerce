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
                        {{ Form::open(['method' => 'GET', 'url' => route('search')] ) }}
                        {{ Form::select('category_id', $categoryList, null, ['id' => 'category_id', 'placeholder' => 'すべてのカテゴリ']) }}
                        {{ Form::text('search_text', null, ['id' => 'search_text']) }}
                        <button type="submit" class="btn btn-success" id="search_Button">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                        
                        {{ Form::close() }}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Thumbnail</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productInfo as $key => $info) 
                                    <tr>
                                        <th>{{ $info->id }}</th>
                                        <th>{{ $info->product_name }}</th>
                                        <th><img src="{{ asset('storage/image/' . $info->product_image) }}" alt="image" width=100 height=100 /></th>
                                        <th>{{ $info->price }}円</th>
                                        <th>
                                            <a href="{{ url('/display/detail/'.$info->id) }}">詳細を表示</a>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="form-group">
                        <a href="{{ route('regist_member') }}">
                            会員登録
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('product') }}">
                            商品登録
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('cart') }}">
                            ショッピングカート
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection