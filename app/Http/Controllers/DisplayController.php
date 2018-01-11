<?php

namespace App\Http\Controllers;

use App\Display;
use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;

class DisplayController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // FIXME:: 今はダイレクトにDB取得しているが既に取得メソッドを記載しているのでそちらから引っ張るようにする。
        $productInfo = DB::table('products')->get();
        return view('/display/index', compact('productInfo'));
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
}
