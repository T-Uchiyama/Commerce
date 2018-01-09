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
                                            <td>{{ $info[0]->product_image }}</td>
                                            <td>現状一律千円</td>
                                            <td>現状一律1個</td>
                                            <td>1000</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan='2'> </td>
                                    <td><strong>合計</strong></td>
                                    <td> 円</td>
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