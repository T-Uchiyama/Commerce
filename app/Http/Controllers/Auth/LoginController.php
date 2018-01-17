<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Abraham\TwitterOAuth\TwitterOAuth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function confirm_facebook()
    {        
        $authURL = 'http://www.facebook.com/dialog/oauth?client_id=' .  \Config::get('const.FACEBOOK_ID') . 
                   '&redirect_uri=' . urlencode(\Config::get('const.OAUTH_CALLBACK'));
        return json_encode($authURL);
    }
    
    public function confirm_twitter()
    {
        //TwitterOAuthインスタンスを新規作成
        $connection = new TwitterOAuth(\Config::get('const.TWITTER_KEY'), \Config::get('const.TWITTER_SECRET'));
        //コールバックURLを設定
        $requestToken = $connection->oauth('oauth/request_token', array('oauth_callback' => \Config::get('const.OAUTH_CALLBACK')));        
        
        if ($requestToken != null && !empty($requestToken)) {
            //セッションにコールバック先で使用する項目を追加
            Session::put('oauth_token', $requestToken['oauth_token']);
            Session::put('oauth_token_secret', $requestToken['oauth_token_secret']);

            //Twitter.com 上の認証画面のURLを取得
            $url = $connection->url('oauth/authenticate', array('oauth_token' => $requestToken['oauth_token']));

            //Twitter.com の認証画面へリダイレクト
            header( 'location: '. $url );
        }
    }
    
    public function confirm_google()
    {        

    }
}
