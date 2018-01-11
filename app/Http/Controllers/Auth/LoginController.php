<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/product';

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
        $app_id = '462326684162348';
        $app_secret = 'f31ef150b526eaa8d67891bad7a47ddb';
        $callback = 'http://commerce.test/display';
        
        $authURL = 'http://www.facebook.com/dialog/oauth?client_id=' . $app_id . '&redirect_uri=' . urlencode($callback);
        return json_encode($authURL);
    }
}
