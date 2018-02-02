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
                <div class="panel-heading">注文一覧</div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PaymentType</th>
                                    <th>TotalPrice</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <th></th>
                                        <th>{{ $paymentTypeArr[$order->payment_type] }}</th>
                                        <th>{{ $order->total_price }}円</th>
                                        <th>
                                            {!! Form::open(['url' => route('display.order.pdf', $order->id), 'method' => 'post']) !!}
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
