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
                <div class="panel-heading">会員登録</div>
                
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <!-- Facebookでログイン -->
                            <div id="status"></div>
                            <div class="fb-login-button" 
                                 scope="public_profile,email"
                                 data-max-rows="1" 
                                 data-size="large" 
                                 data-button-type="continue_with" 
                                 data-show-faces="false" 
                                 data-auto-logout-link="true" 
                                 data-use-continue-as="false"
                                 onlogin="useFacebookLogin()"></div>
                    </div>
                        
                    <!-- Twitterでログイン -->
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button class="twitter">
                                <i class="fa fa-twitter-square" aria-hidden="true">
                                </i>
                                Twitterでログイン
                            </button>
                        </div>
                    </div>

                    <!-- Google+でログイン -->
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button class="google">
                                <i class="fa fa-google-plus-square" aria-hidden="true">    
                                </i>
                                Google+でログイン
                            </button>            
                        </div>
                    </div>
                    
                    <div class="form-group">                           
                        <a href="{{ route('display') }}">
                            商品一覧へ戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection