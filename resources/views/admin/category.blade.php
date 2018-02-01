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
                <div class="panel-heading">カテゴリ一覧</div>

                <div class="panel-body">
                    <h4>CategoryInfoTable</h4> 
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Created_At</th>
                                    <th>Updated_At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categoryMasterData as $masterData)
                                    <tr>
                                        <th>{{ $masterData->id }}</th>
                                        <th>{{ $masterData->name }}</th>
                                        <th>{{ $masterData->created_at  }}</th>
                                        <th>{{ $masterData->updated_at }}</th>
                                        <th>
                                            {!! Form::open(['url' => route('master.category.edit', $masterData->id), 'method' => 'get']) !!}
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
                    
                    <div class="form-group">
                        {!! Form::open(['url' => route('master.category.create'), 'method' => 'get']) !!}
                            <button type="submit" class="btn btn-primary">
                                新規作成
                            </button>
                        {!! Form::close() !!}
                    </div>
                    
                    <div class="form-group">
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
