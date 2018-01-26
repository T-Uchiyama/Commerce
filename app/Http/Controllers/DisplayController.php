<?php

namespace App\Http\Controllers;

use App\Display;
use Auth;
use DB;
use App\User;
use App\Product;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Traits\DataAcquisition;

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
    use DataAcquisition;
    
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
        $is_oauth = !empty($_REQUEST['code']) ? true : false;
        $is_twitterOAuth = !empty($_REQUEST['oauth_token']) ? true : false;
        
        if($is_oauth) {
            //GoogleOAuthかFacebookかチェック
            if (!empty($_GET['state']) && ($_GET['state'] === Session::get('oauth2state'))) {
                // Google
                $connection = new \OAuthClient2Google([
                    'clientId' => \Config::get('const.GOOGLE_ID'),
                    'clientSecret' => \Config::get('const.GOOGLE_SECRET'),
                    'redirectUri' => \Config::get('const.OAUTH_CALLBACK')
                ]);
                
                $token = $connection->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
                
                $_SESSION['token'] = serialize($token);
                $token = unserialize($_SESSION['token']);

                $user = $connection->getResourceOwner($token);
                
                $data = array(
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => $token->getToken(),
                );
                $this->oauthAsUserLogin($data);
            } else {
                // Facebook
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
            }
            
        } else if ($is_twitterOAuth) {            
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

        $productInfo = $this->getProductList();
        $categoryList = $this->getCategoryData();
        return view('display.index', compact('productInfo', 'categoryList'));
    }
    
    /**
     * 詳細画面の表示
     * 
     * @param  integer $id 商品ID
     * @return \Illuminate\Http\Response
     */
    public function getDetail($id = 0)
    {
        $product = $this->getProductList($id);
        $productImageInfo = \App\Product::find($id)->productImages()->get();
        return view('display.detail', compact('product', 'productImageInfo'));
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
     * レジ画面の表示
     * 
     * @return \Illuminate\Http\Response
     */
    public function getCheckout(Request $request)
    {
        $productData = array();
        $cartData = $request->session()->get('cart');
        
        $productData = $this->combineSessionArray($cartData);
        $total = $request->session()->get('total');
    
        return view('display.checkout', compact('productData', 'total'));
    }
    
    /**
     * ショッピングカート一覧の表示
     * 
     * @return \Illuminate\Http\Response
     */
    public function getCart()
    {    
        $productData = array();
        $cartData = Session::get('cart');
        
        $productData = $this->combineSessionArray($cartData);
        
        return view('display.cart', compact('productData'));
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
            $product = $this->getProductList($id);
                           
            $request->session()->push('cart', array(
                'product_id' => $id,
                'name' => $product->product_name,
                'price' => $product->price,
                'stock' => $product->stock,
                'added' => 1,
            ));

            return redirect()->action('DisplayController@getDetail', ['id' => $id])
                             ->with('message', 'カートに追加しました');
        }
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
        $sessionData = $request->session()->get('cart');
        $changeNumData = $request->session()->get('changeCart');

        foreach ($sessionData as $key => $value) {
            if ($value['product_id'] == $id) {
                unset($sessionData[$key]);
                // ショッピングカート一覧の増減ボタンで購入個数を変更している場合は合わせて削除
                if (array_key_exists($value['name'], $changeNumData)) {
                    unset($changeNumData[$value['name']]);
                    
                    $request->session()->forget('changeCart');
                    $request->session()->put('cart', $changeNumData);
                }
            }
        }

        $request->session()->forget('cart');
        $request->session()->put('cart', $sessionData);

        return redirect()->action('DisplayController@getCart');
    }
    
    /**
     * 購入手続き(メール送信し購入内容をユーザーに)
     * 
     * @param  Request $request リクエストデータ
     * 
     * @return  \Illuminate\Http\Response
     */
    public function purchase(Request $request)
    {
        if (!Auth::check()) {
            return view('register.index')->withErrors(['notLogin' => '商品購入のためにはログインしてください']);
        }
        
        $this->validate($request, [
            'destination_name' => 'required',
            'postal_code'      => 'numeric|required',
            'prefecture'       => 'required',
            'address1'         => 'required',
            'address2'         => 'required',
            'address3'         => 'required',
            'payment_type'     => 'required',
        ]);
        $productData = array();
        $orderDetailData = array();
        $paymentTypeArr = array(
            1 => 'クレジット決済', 
            2 => '代引き',
            3 => 'コンビニ決済'
        );
        
        $order_status = 1;
        $cartData = $request->session()->get('cart');
        $total = $request->session()->get('total');
        
        $productData = $this->combineSessionArray($cartData);
        
        $address = $request->prefecture . $request->address1 . $request->address2 . $request->address3;
        $postal_code = $request->postal_code;
        $item = '';
        
        foreach ($productData as $product) {
            $stockData = $this->getProductList($product->product_id);
            
            if ($stockData->stock > $product->added) {
                $orderDetailData[] = array(
                    'product_id'   => $product->product_id, 
                    'price'        => $product->price,
                    'product_name' => $product->product_name,
                    'purchase_num' => $product->added,
                    'remain'       => $stockData->stock - $product->added,
                );
                $order_status = 2;
                
                $item = $item . $product->product_name . ' : ' . $product->price . ' × ' .
                        $product->added . "\t" . '計: ' . $product->price * $product->added . ',' . PHP_EOL;
            } else {
                return redirect()->action('DisplayController@getCheckout')
                                 ->withErrors( ['stock_shortages' => '大変申し訳ございません。ご購入希望商品の在庫が不足しております。']);     
            }
        }
        
        $purchase_content = 'お買い上げ商品: ' . PHP_EOL . $item .
                            '合計金額: ' . $total . PHP_EOL .
                            '支払い方法: ' . $paymentTypeArr[$request->payment_type] . PHP_EOL .
                            '郵便番号: ' . $request->postal_code . PHP_EOL .
                            'お届け先住所: ' . $request->prefecture .
                                             $request->address1 . 
                                             $request->address2 . 
                                             $request->address3 .PHP_EOL;
        
         $lastInsertId = DB::table('orders')->insertGetId([
             'user_id'   => \Auth::id(), 
             'purchase_content' => $purchase_content,
             'total_price' => $total,
             'payment_type' => $request->payment_type,
             'address' => $request->prefecture . $request->address1 . $request->address2 . $request->address3,
             'postal_code' => $request->postal_code,
             'order_status' => $order_status,
             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
             'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
         ]);

         foreach ($orderDetailData as $key => $value) {
             DB::table('order_details')->insert([
                 'order_id' => $lastInsertId,
                 'product_id' => $value['product_id'], 
                 'price' => $value['price'], 
                 'product_name' => $value['product_name'],
                 'purchase_num' => $value['purchase_num'],
                 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
             ]);
             DB::table('products')->where('id', $value['product_id'])->update(['stock' => $value['remain']]);
         }


        // プレーンテキストタイプでは改行が表示されないのはLaravelの仕様とのこと。
        $data = array(
            'Content' => \Auth::user()->name . '様' . PHP_EOL .
                         '-------------------------------------------------------------------------' . PHP_EOL .
                         '今回お買い上げの商品は' . PHP_EOL . $item .
                         '合計金額は' . $total . '円です。' . PHP_EOL .
                         '-------------------------------------------------------------------------' . PHP_EOL .
                         'お届け先 :' . "\t" . $request->destination_name . PHP_EOL .
                          "\t" . $request->postal_code . PHP_EOL .
                          "\t" . $request->prefecture . $request->address1 . $request->address2 . $request->address3 . PHP_EOL .
                         '支払い方法: ' . $paymentTypeArr[$request->payment_type] . PHP_EOL .
                         '-------------------------------------------------------------------------' . PHP_EOL .
                         'お買い上げありがとうございました。'
        );
        
        $request->session()->put('mail_content', $data);
        Mail::to(\Auth::user()->email)->send(new OrderShipped($request));
        $request->session()->forget('cart');
        $request->session()->forget('total');
        $request->session()->forget('changeCart');
        
        $order_status = 3;
        DB::table('orders')->where('id', $lastInsertId)->update(['order_status' => $order_status, 'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),]);
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
    /**
     * 同じ項目を結合させ、ショッピングカートデータに情報を付随
     * 
     * @param   $cartData ショッピングカートデータ
     * 
     * @return $uniqueProducts 単一化したショッピングカートデータ
     */
    public function combineSessionArray($cartData)
    {
        if (!empty($cartData)) {
            $total = 0;
            foreach ($cartData as $data) {
                $productData[] = \App\Product::find($data['product_id'])->productImages()->first();
            }

            $tmp = [];
            $uniqueProducts = [];
            $countArray = [];
            $sessionData = Session::get('changeCart');
            
            foreach ($productData as $product) {
                if (!in_array($product['product_id'], $tmp)) {
                    $tmp[] = $product['product_id'];
                    $uniqueProducts[] = $product;
                } else {
                    array_push($countArray, $product['product_id']);
                }
            }
            $countArray = array_count_values($countArray);
            
            foreach ($uniqueProducts as $key => $value) {
                foreach ($cartData as $data) {
                    if($value->product_id == $data['product_id']) {
                        $value->product_name = $data['name'];
                        $value->price = $data['price'];
                        $value->stock = $data['stock'];
                        if (!empty($countArray) && !empty($countArray[$data['product_id']])) {
                            $value->added = $data['added'] + $countArray[$data['product_id']];
                        } else {
                            $value->added = $data['added'];
                        }
                        if (!empty($sessionData) && array_key_exists($data['name'], $sessionData)) {
                            $value->added = $sessionData[$data['name']];
                        }
                    }
                }
                $total +=  $value->price * $value->added;
            }
            // セッションに合計金額格納
            Session::put('total', $total);
            
            return $uniqueProducts;
        } else {
            return array();
        }
    }
    
    /**
     * ボタン押下によるショッピングカートの増減結果をセッションで保持
     *
     * @param Request $request リクエストデータ
     * 
     * @return 増減結果をまとめたレスポンスデータ
     */
    public function changeCartNum(Request $request)
    {
        //  セッションにカート変更情報が存在するかチェック
        if (!$request->session()->has('changeCart')) {
            $request->session()->put('changeCart', array());
        }
        // セッション内にデータが存在しているかチェック
        if (!empty($request->session()->get('changeCart'))) {
            $sessionData = $request->session()->get('changeCart');
            
            if (array_key_exists($request->input('product_name'), $sessionData)) {
                // 既に配列に保存されている場合は値のみを修正
                $sessionData = array_merge($sessionData, array(
                    $request->input('product_name') => $request->input('added'),
                ));
            } else {
                // 配列が存在しているが、キーが存在しない場合は配列に追記
                $sessionData += array(
                    $request->input('product_name') => $request->input('added'),
                );
            }
            $request->session()->forget('changeCart');
            $request->session()->put('changeCart', $sessionData);
        } else {
            // 新規の場合にはデータを特に変化させることなく格納
            $request->session()->put('changeCart', array(
                $request->input('product_name') => $request->input('added'),
            ));
        }
        
        return json_encode($request->session()->get('changeCart'));        
    }
    
    /**
     * 検索文字を取得しクエリを用い検索結果の表示
     * @param  Request $request リクエストデータ
     * 
     * @return \Illuminate\Http\Response
     */
    public function getQuerySearch(Request $request)
    {
        $categoryId = $request->category_id;
        $searchText = $request->search_text;
        
        $productInfo = $this->getProductList();
        $categoryList = $this->getCategoryData();
        $paginate_flg = false;
        if (!empty($categoryId) && !empty($searchText)) {
            $productInfo = \App\Product::where('category_id', $categoryId)
                                        ->orWhere('product_name', 'LIKE', "%$searchText%")
                                        ->paginate(10);
            $paginate_flg = true;
        } else if (!empty($categoryId) && empty($searchText)) {
            $productInfo = \App\Product::where('category_id', $categoryId)
                                        ->paginate(10);
            $paginate_flg = true;
        } else if (empty($categoryId) && !empty($searchText)) {
            $productInfo = \App\Product::where('product_name', 'LIKE', "%$searchText%")
                                        ->paginate(10);
            $paginate_flg = true;
        }
        
        if($paginate_flg) {
            foreach ($productInfo as $key => $value) {
                $productInfo[$key]->product_image = \App\Product::find($value->id)->productImages()->first()->product_image; 
            }
        }

        return view('display.index', compact('productInfo', 'categoryList'));
    }
}
