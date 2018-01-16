@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">ユーザー情報一覧</div>

                <div class="panel-body">
                    <h4>UserInfoTable</h4> 
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>RememberToken</th>
                                    <th>Created_At</th>
                                    <th>Updated_At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userMasterData as $masterData)
                                    <tr>
                                        <th>{{ $masterData->id }}</th>
                                        <th>{{ $masterData->name }}</th>
                                        <th>{{ $masterData->email }}</th>
                                        <th>{{ $masterData->password }}</th>
                                        <th>{{ $masterData->remember_token }}</th>
                                        <th>{{ $masterData->created_at  }}</th>
                                        <th>{{ $masterData->updated_at }}</th>
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
