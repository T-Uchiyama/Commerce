@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>This is AdminSite Home</h4> 
                    
                    <div class="from-group">
                        <a href="{{ route('user_info') }}">
                            ユーザー情報一覧を表示
                        </a>
                    </div>
                    <div class="from-group">
                        <a href="{{ route('product_info') }}">
                            商品在庫一覧を表示
                        </a>
                    </div>
                    <div class="from-group">
                        <a href="{{ route('category_info') }}">
                            カテゴリ一覧を表示
                        </a>
                    </div>
                    <div class="from-group">
                        <a href="{{ route('order_info') }}">
                            注文一覧を表示
                        </a>
                    </div>
                    <div class="from-group">
                        <a href="{{ route('display') }}">
                            商品購入ページへ遷移
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
