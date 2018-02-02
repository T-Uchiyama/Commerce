<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Abraham\TwitterOAuth\TwitterOAuth;
use Session;
use Illuminate\Http\Request;

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
    
    /**
     * FacebookOAuth画面の表示
     * 
     * @return \Illuminate\Http\Response
     */
    public function confirm_facebook()
    {        
        $authURL = 'http://www.facebook.com/dialog/oauth?client_id=' .  \Config::get('const.FACEBOOK_ID') . 
                   '&redirect_uri=' . urlencode(\Config::get('const.OAUTH_CALLBACK'));
        return redirect($authURL);
    }
    
    /**
     * TwitterOAuth画面の表示
     * 
     * @return \Illuminate\Http\Response
     */
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
            return redirect($url);
        }
    }
    
    /**
     * Google+OAuth画面の表示
     * 
     * @return \Illuminate\Http\Response
     */
    public function confirm_google()
    {
        // GoogleOAuth2インスタンス新規生成
        $connection = new \OAuthClient2Google([
            'clientId' => \Config::get('const.GOOGLE_ID'),
            'clientSecret' => \Config::get('const.GOOGLE_SECRET'),
            'redirectUri' => \Config::get('const.OAUTH_CALLBACK')
        ]);
        
        $authUrl = $connection->getAuthorizationUrl();
        Session::put('oauth2state',$connection->getState());
        return redirect($authUrl);
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('display')->with('status', 'ログアウトしました');
    }
}
