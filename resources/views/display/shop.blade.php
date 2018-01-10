@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Shop</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>商品名</th>
                                    <th>単価</th>
                                    <th>数量</th>
                                    <th>小計</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productInfo as $key => $info)
                                    @if (!empty($info[0]))
                                        <tr>
                                            @if (!empty($info[0]->product_name))
                                                <td>{{ $info[0]->product_name }}</td>
                                            @else
                                                <td>{{ $info[0]->product_image }}</td>
                                            @endif
                                            @if (($info[0]->price) != 0)
                                                <td>{{ $info[0]->price }}円</td>
                                            @else
                                                <td>1000円</td>
                                            @endif
                                            <td>{{ $info[0]->number }}個</td>
                                            <td>{{ $info[0]->subtotal }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan='2'> </td>
                                    <td><strong>合計</strong></td>
                                    <td> {{ $total }}円</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="form-group">
                          <a href="{{ url('/display') }}">商品一覧に戻る</a>
                          <a href="{{ url('/display/emptyCart/'.$page_id) }}">カートを空にする</a>
                              <form action="{{ url('/display/purchase/'.$page_id) }}" method="POST" class="form-horizontal">
                                  {{ csrf_field() }}
                                  <button type="submit" class="btn btn-info">
                                      <i class="fa fa-btn fa-trash"></i>購入する
                                  </button>
                              </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection