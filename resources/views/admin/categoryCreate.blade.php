@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Category Create</div>

                <div class="panel-body">
                    {!! Form::open(['url' => route('master.category.create'), 'method' => 'post', 'files' => true]) !!}

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
                    
                    <div class="form-group">
                        {!! Form::label('categoryName', 'カテゴリ名:') !!}
                        {!! Form::text('categoryName', null, ['class' => 'form-control']) !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::submit('カテゴリ新規作成', ['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                
                <div class="form-group">
                    <a href="{{ route('category_info') }}">
                        カテゴリ一覧表示画面へ戻る
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>                           
                    <a href="{{ route('home') }}">
                        管理者トップページへ戻る
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection