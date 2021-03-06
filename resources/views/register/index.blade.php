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
                <div class="panel-heading">Register Member</div>
                
                <div class="panel-body">
                    @include('register.login')
                    
                    <div class="form-group" align="center">
                        <label>下記サービスアカウントでのログインはこちら</label>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a href="{{ url('/confirm_facebook') }}">
                                <button class="facebook">
                                    <i class="fa fa-facebook-official" aria-hidden="true">
                                    </i>
                                    Facebookでログイン
                                </button>
                            </a>
                        </div>
                    </div>
                        
                    <!-- Twitterでログイン -->
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a href="{{ url('/confirm_twitter') }}">
                                <button class="twitter">
                                    <i class="fa fa-twitter-square" aria-hidden="true">
                                    </i>
                                    Twitterでログイン
                                </button>
                            </a>
                        </div>
                    </div>

                    <!-- Google+でログイン -->
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a href="{{ url('/confirm_google') }}">
                                <button class="google">
                                    <i class="fa fa-google-plus-square" aria-hidden="true">    
                                    </i>
                                    Google+でログイン
                                </button>  
                            </a>
                        </div>
                    </div>
                    
                    <div class="form-group">                           
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