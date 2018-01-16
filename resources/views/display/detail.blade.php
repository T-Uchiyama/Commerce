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
                                        <th><img src="{{ asset('storage/image/' . $product->product_image) }}" alt="image" width="300px" height="300px"/></th>
                                        <th>{{ $product->product_name }}</th>
                                        <th>{{ $product->price }}円</th>
                                        <th>残り{{ $product->price }}点</th>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="form-group">
                        <form action="{{ url('/display/cart/'.$product->id)}}" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-btn fa-trash"></i>ショッピングカートへ追加
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection