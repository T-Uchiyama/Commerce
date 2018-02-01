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
                <div class="panel-heading">Product Detail</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <th>
                                            <div class="swiper-container">
                                                <div class="swiper-wrapper">
                                                    @foreach ($productImageInfo as $imageInfo)                                        
                                            		<div class="swiper-slide">
                                                        <img src="{{ asset('storage/image/' . $imageInfo->product_image) }}" alt="image" width="300px" height="300px"/>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="swiper-button-prev">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 44">
                                                        <path class="arrow arrow-left" d="M0,22L22,0l2.1,2.1L4.2,22l19.9,19.9L22,44L0,22L0,22L0,22z" />
                                                    </svg>
                                                </div>
                                                <div class="swiper-button-next">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 44">
                                                        <path class="arrow arrow-right" d="M27,22L27,22L5,44l-2.1-2.1L22.8,22L2.9,2.1L5,0L27,22L27,22z" />
                                                    </svg>
                                                </div>

                                            </div>
                                        </th>
                                        <th><span id="product_detail_name">{{ $product->product_name }}</span></th>
                                        <th><span id="product_detail_price">{{ $product->price }}円</span></th>
                                        <th><span id="product_detail_stock">残り{{ $product->stock }}点</span></th>
                                    </tr>
                            </tbody>
                        </table>
                    </div>

                    <script type="text/javascript">
                        setStorage();
                    </script>
                    <div class="form-group">
                        <form action="{{ url('/display/shoppingcart/'.$product->id)}}" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-shopping-cart"></i>ショッピングカートへ追加
                            </button>
                        </form>
                        
                        <a href="{{ route('display') }}">
                            商品一覧へ戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection