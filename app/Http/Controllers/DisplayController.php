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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // セッションにカート追加。
            if (!$request->session()->has('cart')) {
                $request->session()->put('cart', array());
            } 
            $request->session()->push('cart', $id);
            $sessionArr = $request->session()->get('cart');
            
            for ($i = 0; $i < count($sessionArr); $i++) {
                array_push($productInfo, DB::table('products')->where('id', $sessionArr[$i])
                                                              ->select('id', 'product_image')
                                                              ->get());
            }
            
            // TODO::同IDが存在した場合は個数を増やし、データ上の重複をなくす。
        }
        $page_id = $id;
        return view('/display/shop', compact('productInfo', 'page_id'));
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
        $request->session()->put('purchased', array());
        for ($i = 0; $i < count($idArr); $i++) {
            $request->session()->push('purchased', $idArr[$i]);
        }
        $purchaseNum = Session::get('purchased', '');
        
        $item = '';
        foreach (array_unique($purchaseNum) as $key) {
            $product = DB::table('products')->where('id', $key)
                                            ->select('product_image')
                                            ->first();
            $item = $item . $product->product_image . ',' . "\n";
        }
        
        // TODO:: メール本文に改行が出力されない。
        //        個数や購入後のアイテムソートを後に追加。
        $data = array(
            'Content' => '今回お買い上げの商品は' . "\n" . $item .' です。'
        );
        $request->session()->put('mail_content', $data);
        Mail::to(\Auth::user()->email)->send(new OrderShipped($request));
        return redirect()->action('DisplayController@index')
                         ->with('status', 'Send Mail!!');        
    }
}
