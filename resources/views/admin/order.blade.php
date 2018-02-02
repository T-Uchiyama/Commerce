@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
            <div class="panel panel-default">
                <div class="panel-heading">商品在庫一覧</div>

                <div class="panel-body">
                    <h4>ProductInfoTable</h4> 
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>userId</th>
                                    <th>Content</th>
                                    <th>TotalPrice</th>
                                    <th>PaymentType</th>
                                    <th>Address</th>
                                    <th>PostalCode</th>
                                    <th>OrderStatus</th>
                                    <th>Created_At</th>
                                    <th>Updated_At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderMasterData as $masterData)
                                    <tr>
                                        <th>{{ $masterData->id }}</th>
                                        <th>{{ $masterData->user_id }}</th>
                                        <th>{{ $masterData->purchase_content }}</th>
                                        <th>{{ $masterData->total_price }}円</th>
                                        <th>{{ $masterData->payment_type }}</th>
                                        <th>{{ $masterData->address }}</th>
                                        <th>{{ $masterData->postal_code }}</th>
                                        <th>{{ $masterData->order_status }}</th>
                                        <th>{{ $masterData->created_at }}</th>
                                        <th>{{ $masterData->updated_at }}</th>
                                        <th>
                                            {!! Form::open(['url' => route('order.master.pdf', $masterData->id), 'method' => 'post']) !!}
                                            {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-info">
                                                        領収書
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
