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
                                    <th>ImageName</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Created_At</th>
                                    <th>Updated_At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productMasterData as $masterData)
                                    <tr>
                                        <th>{{ $masterData->id }}</th>
                                        <th>
                                            <img src="{{ asset('storage/image/' . $masterData->product_image) }}" alt="image" width=100 height=100 value="{{ $masterData->product_image }}" />
                                        </th>
                                        <th>{{ $masterData->product_name }}</th>
                                        <th>{{ $masterData->price }}円</th>
                                        <th>{{ $masterData->stock }}個</th>
                                        <th>{{ $masterData->created_at  }}</th>
                                        <th>{{ $masterData->updated_at }}</th>
                                        <th>
                                            {!! Form::open(['url' => route('master.edit', $masterData->id), 'method' => 'get']) !!}
                                                    <button type="submit" class="btn btn-info">
                                                        データ編集
                                                    </button>
                                            {!! Form::close() !!}
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="from-group">
                        <a href="{{ route('home') }}">
                            管理者トップページに戻る
                        </a>
                        {!! Form::open(['url' => route('csv'), 'method' => 'post']) !!}
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-info">
                                CSVダウンロード
                            </button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
