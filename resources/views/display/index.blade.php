@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Display Screen</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Purchase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- TODO:: 現状はサムネイルのみとなっているが金額と売買誓約フラグを後に追加し購入の流れを作成 -->
                                @foreach ($productInfo as $info) 
                                    <tr>
                                        <th>{{ $info->id }}</th>
                                        <th><img src="{{ asset('storage/image/' . $info->product_image) }}" alt="image" width=100 height=100 /></th>
                                        <th>
                                            <form action="{{ url('/display/purchase/'.$info->id) }}" method="POST" class="form-horizontal">
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-info">
                                                    <i class="fa fa-btn fa-trash"></i>Purchase
                                                </button>
                                            </form>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection