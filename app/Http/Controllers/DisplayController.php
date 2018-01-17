<?php

namespace App\Http\Controllers;

use App\Display;
use Auth;
use DB;
use App\User;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

use Abraham\TwitterOAuth\TwitterOAuth;

class DisplayController extends Controller
{    
    use RegistersUsers;
    use AuthenticatesUsers {
        AuthenticatesUsers::guard insteadof RegistersUsers; 
        AuthenticatesUsers::redirectPath insteadof RegistersUsers; 
    }
    
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/display';
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'password' => 'string|min:6',
        ]);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $is_facebook = !empty($_REQUEST['code']) ? true : false;
        $is_twitter = !empty($_REQUEST['oauth_token']) ? true : false;
        if($is_facebook) {
            $code = $_REQUEST['code'];
            
            $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='.
                         \Config::get('const.FACEBOOK_ID') . '&redirect_uri=' . urlencode(\Config::get('const.OAUTH_CALLBACK')) . 
                         '&client_secret='. \Config::get('const.FACEBOOK_SECRET') . '&code=' . $code;
                         
            // access_tokenの取得
            $access_token = file_get_contents($token_url);
            $start_needle = strpos($access_token, ':');
            $end_needle = strpos($access_token, ',');
            // access_tokenの値のみを取得するように文字列切り取り
            $access_token = substr($access_token, $start_needle + 2, ($end_needle - $start_needle - 3));

            // ユーザ情報をjsonにて取得しdecode
            $user_json = file_get_contents('https://graph.facebook.com/me/?fields=email,id,name&access_token=' . $access_token);
            $user = json_decode($user_json);
            // facebookからユーザー登録に必要な情報を取得            
            $data = array(
                'name' => $user->name,
                'email' => $user->email,
                'password' => $access_token,
            );
            $this->oauthAsUserLogin($data);
            
        } else if ($is_twitter) {            
            $connection = new TwitterOAuth(
                \Config::get('const.TWITTER_KEY'),
                \Config::get('const.TWITTER_SECRET'), 
                Session::get('oauth_token'), 
                Session::get('oauth_token_secret')
            );
            
            // access_tokenの取得
            $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
            
            //ユーザー情報取得のために取得したアクセストークンを用い再度インスタンスを作成
            $getUserConnection = new TwitterOAuth(
                \Config::get('const.TWITTER_KEY'),
                \Config::get('const.TWITTER_SECRET'), 
                $access_token['oauth_token'], 
                $access_token['oauth_token_secret']
            );
            
            //  twitterからユーザー登録に必要な情報を取得
            $userData = $getUserConnection->get("account/verify_credentials", ['include_entities'=> 'false', 'include_email'=> 'true']);
            
            // TODO:: 本来であればemailにはTwitterに登録しているメールアドレスを追加するのだが
            //        TwitterOAuthから権限取得しなければメールアドレスを取得できないため
            //        現在はテストのためランダムで生成したアドレスを格納
            $data = array(
                'name' => $userData->screen_name,
                'email' => uniqid().'@test.com',
                'password' => $access_token['oauth_token'],
            );
            
            $this->oauthAsUserLogin($data);
            
        }

        // FIXME:: 今はダイレクトにDB取得しているが既に取得メソッドを記載しているのでそちらから引っ張るようにする。
        $productInfo = DB::table('products')->get();
        return view('/display/index', compact('productInfo'));
    }
    
    /**
     * 詳細画面の表示
     * 
     * @param  integer $id 商品ID
     * @return \Illuminate\Http\Response
     */
    public function getDetail($id = 0)
    {
        $product = DB::table('products')->where('id', $id)
                                        ->select('id', 'product_name', 'product_image', 'price', 'stock')
                                        ->first();
        return view('display.detail', compact('product'));
    }
    
    /**
     * 会員登録画面の表示
     * 
     * @return \Illuminate\Http\Response
     */
    public function getRegistMember()
    {
        return view('register.index');
    }
    
    /**
     * ショッピングカート追加
     *
     * @param  Request $request リクエストデータ
     * @param  integer $id 商品ID
     * @return \Illuminate\Http\Response
     */
    public function addShoppingCart(Request $request, $id = 0)
    {
        if ($request->isMethod('post')) {
            // セッションにカート追加
            if (!$request->session()->has('cart')) {
                $request->session()->put('cart', array());
            } 
            $product = DB::table('products')->where('id', $id)
                                            ->select('product_name', 'price')
                                            ->first();
            
            $request->session()->push('cart', array(
                'id' => $id,
                'name' => $product->product_name,
                'price' => $product->price,
                'stock' => 1,
            ));

            return redirect()->action('DisplayController@getDetail', ['id' => $id])
                             ->with('message', 'カートに追加しました');
        }
    }
    
    /**
     * Show the application dashboard.
     *
     * @param  Request $request リクエストデータ
     * @param  integer $id 商品ID
     * @return \Illuminate\Http\Response
     */
    public function shop(Request $request, $id = 0)
    {
        $productInfo = array();
        $total = 0;
        if ($request->isMethod('post')) {
            // セッションにカート追加
            if (!$request->session()->has('cart')) {
                $request->session()->put('cart', array());
            } 
            $request->session()->push('cart', $id);
            $sessionArr = $request->session()->get('cart');
            
            for ($i = 0; $i < count($sessionArr); $i++) {
                array_push($productInfo, DB::table('products')->where('id', $sessionArr[$i])
                                                              ->select('id', 'product_image', 'price', 'product_name')
                                                              ->get());
            }
            
            //ObjectをArrayにキャスト
            $productArr = json_decode(json_encode($productInfo), true);
            
            $array = array();
            foreach ($productArr as $productData) {
                array_push($array, $productData[0]['product_image']);
            }
            $result = array_count_values($array);
            $request->session()->put('number', $result);
            $productInfo = array_unique($productInfo);

            // productInfoに個数と小計項目を追加
            foreach ($productInfo as $productData) {
                if (array_key_exists($productData[0]->product_image, $result)) {
                    $productData[0]->number =  $result[$productData[0]->product_image];
                    if ($productData[0]->price != 0) {
                        $productData[0]->subtotal = $productData[0]->price * $result[$productData[0]->product_image];
                    } else {
                        //FIXME:: このelseは値が入っていないパターンのために作成。
                        //        データが正確であれば不要。
                        $productData[0]->subtotal = 1000 * $result[$productData[0]->product_image];
                    }
                    $total += $productData[0]->subtotal;
                }
            }
            $request->session()->put('total', $total);
        }
        $page_id = $id;
        $total = $request->session()->get('total', 0);
        return view('/display/shop', compact('productInfo', 'page_id', 'total'));
    }
    /**
     * カートを空にする。
     * 
     * @param  Request $request リクエストデータ
     * @param  integer $id PageID
     * 
     * @return \Illuminate\Http\Response
     */
    public function emptyCart(Request $request, $id)
    {
        $request->session()->forget('cart');
        $request->session()->forget('total');
        $request->session()->forget('number');
        return redirect()->action(
            'DisplayController@shop', ['id' => $id]
        );
    }
    
    /**
     * 購入手続き(メール送信し購入内容をユーザーに)
     * 
     * @param  Request $request リクエストデータ
     * @param   $id      商品ID
     * 
     * @return  \Illuminate\Http\Response
     */
    public function purchase(Request $request, $id)
    {
        $idArr = $request->session()->get('cart');
        $numStr = $request->session()->get('number');
        $total = $request->session()->get('total');
        $request->session()->put('purchased', array());
        for ($i = 0; $i < count($idArr); $i++) {
            $request->session()->push('purchased', $idArr[$i]);
        }
        $purchaseNum = Session::get('purchased', '');
        
        $item = '';
        foreach (array_unique($purchaseNum) as $key) {
            $product = DB::table('products')->where('id', $key)
                                            ->select('product_image', 'product_name', 'price')
                                            ->first();
            $item = $item . $product->product_name . ' × ' . 
                    $numStr[$product->product_image] . "\t" . '計: ' . $product->price * $numStr[$product->product_image] .
                    ',' . PHP_EOL;
        }
        
        // プレーンテキストタイプでは改行が表示されないのはLaravelの仕様とのこと。
        $data = array(
            'Content' => '今回お買い上げの商品は' . PHP_EOL . $item  . PHP_EOL .
                         '合計金額は' . $total . '円です。' . PHP_EOL .
                         'お買い上げありがとうございました。'
        );
        $request->session()->put('mail_content', $data);
        Mail::to(\Auth::user()->email)->send(new OrderShipped($request));
        $request->session()->forget('cart');
        $request->session()->forget('total');
        $request->session()->forget('number');
        return redirect()->action('DisplayController@index')
                         ->with('status', 'Send Mail!!');        
    }
    
    /**
     * OAuthで取得したユーザー情報を用いてCommerceサイトへログイン
     * 
     * @param  $oauthData 各種OAuthのデータ
     */
    public function oauthAsUserLogin($oauthData)
    {
        $this->validator($oauthData)->validate();
        if (empty(DB::select('select * from users where name = :name', ['name' => $oauthData['name']]))) {
            // 空の場合には新規登録   
            event(new Registered($user = User::create([
                'name' => $oauthData['name'],
                'email' => $oauthData['email'],
                'password' => bcrypt($oauthData['password']),
            ])));
            
        } else {
            // TODO::本来はemailも条件に含めてより一意の条件にしたいが、
            //       Twitterの登録アドレスを上記に述べた理由でランダム生成しているためnameだけで取得
            // event(new Registered($user = User::firstOrNew([
            //     ['name', '=', $oauthData['name']],
            //     ['email', '=', $oauthData['email']],
            // ])));
            event(new Registered($user = User::firstOrNew([
                ['name', '=', $oauthData['name']],
            ])));
        }
        $this->guard()->login($user);
    }
}
