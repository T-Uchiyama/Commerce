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
                <div class="panel-heading">Checkout</div>
                <div class="panel-body">
                    {!! Form::open(['url' => route('checkout'), 'metdod' => 'post', 'files' => true]) !!}

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
                    <div id="enclosure">
                        <center>
                            {!! Form::label('address_info', 'お届け先住所') !!}
                        </center>
                        
                        <div class="form-group">
                            {!! Form::label('destination_name', '宛先名:') !!} 
                            {!! Form::text('destination_name') !!}様                    
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label('postal_code', '郵便番号:') !!} 
                            {!! Form::text('postal_code', null, ['onkeyup'=>"AjaxZip3.zip2addr(this,'','prefecture','address1','address2','address3')"]); !!}
                            <h6><font color="#3097d1">※郵便番号を入力後に自動で住所を取得します</font></h6>
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label('prefecture', '都道府県:') !!} 
                            {!! Form::text('prefecture') !!}
                            <h6>例:東京都</h6>
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label('address1', '住所(市区町村郡):') !!} 
                            {!! Form::text('address1') !!}
                            <h6>例:○○区</h6>
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label('address2', '住所(町名番地):') !!} 
                            {!! Form::text('address2') !!}
                            <h6>例:○○町</h6>
                        </div>
                        
                        <div class="form-group">
                            {!! Form::label('address3', '住所(建物名):') !!} 
                            {!! Form::text('address3') !!}
                            <h6>例:○-○-○ ○○タワー808</h6>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('payment_type', '支払い方法選択:') !!} 
                        {!! Form::select('payment_type', [1 => 'クレジット決済', 2 => '代引き', 3 => 'コンビニ決済'], null, ['placeholder' => '選択してください']) !!}                    
                    </div>

                    <div class="form-group">
                        {!! Form::label('purchase_content', '購入情報:') !!} 
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                </thead>
                                <tbody>
                                    @foreach ($productData as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('storage/image/' . $product->product_image) }}" alt="image" width=100 height=100 />
                                            </td>
                                            <td>
                                                <p>{{ $product->product_name }}</p>
                                                <p>単価: {{ $product->price }}円</p>
                                                <p>{{ $product->added }}点購入</p>
                                                <p>残り{{ $product->stock - $product->added }}点</p>
                                            </td>
                                            <td>小計: {{ $product->price * $product->added }}円</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>合計: {{ $total }}円</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::submit('購入', ['class' => 'btn btn-info']) !!}
                    </div>
                    {!! Form::close() !!}
                    
                    <div class="form-group">
                        <a href="{{ route('cart') }}">
                            ショッピングカート一覧へ戻る
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        
                        <a href="{{ route('display') }}">
                            商品一覧へ戻る
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection