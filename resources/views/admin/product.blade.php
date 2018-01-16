@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">商品在庫一覧</div>

                <div class="panel-body">
                    <h4>ProductInfoTable</h4> 
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>ImageName</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Created_At</th>
                                    <th>Updated_At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productMasterData as $masterData)
                                    <tr>
                                        <th>{{ $masterData->id }}</th>
                                        <th>{{ $masterData->product_name }}</th>
                                        <th>{{ $masterData->product_image }}</th>
                                        <th>{{ $masterData->price }}円</th>
                                        <th>{{ $masterData->stock }}個</th>
                                        <th>{{ $masterData->created_at  }}</th>
                                        <th>{{ $masterData->updated_at }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="from-group">
                        <a href="{{ route('home') }}">
                            管理者トップページに戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
