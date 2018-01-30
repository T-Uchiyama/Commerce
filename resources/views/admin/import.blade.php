@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">CSV Import Form</div>

                <div class="panel-body">
                    {!! Form::open(['url' => route('import'), 'method' => 'post', 'files' => true]) !!}
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
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>項目</th>
                                        <th>値</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="form-group">
                                        <tr>
                                            <th>
                                                {!! Form::label('pca_product_id', 'ファイル名:') !!}
                                            </th>
                                            <td>
                                                {!! Form::file('file') !!}
                                            </td>
                                        </tr>
                                    </div>
                                </tbody>
                            </table>
                            
                            <div class="from-group">
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
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
