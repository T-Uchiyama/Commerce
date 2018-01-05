<?php

namespace App\Http\Controllers;

use App\Display;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DisplayController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO:: 今はダイレクトにDB取得しているが既に取得メソッドを記載しているのでそちらから引っ張るようにする。
        $productInfo = DB::table('products')->get();
        return view('/display/index', compact('productInfo'));
    }
    
    /**
     * 購入手続き
     * 
     * @param  Request $request リクエストデータ
     * @param   $id      商品ID
     * 
     * @return [type]           [description]
     */
    public function purchase(Request $request, $id)
    {
        /**
         * 現時点で必要な情報
         * $id = id;
         * 購入者情報 ← セッションID + cookieで実現
         * 
         */
        
        // 名前を取得
        $user_id = $request->session()->get('user_id', function () {
            return \Auth::user()->id;
        });
        // セッションIDの取得
        $session_id = $request->session()->getId();
        
        // セッション情報をDB側から取得 
        $sessionInfo = DB::table('sessions')->select('id')->get();        
        $data = $request->session()->all();
        
        if (DB::table('sessions')->where('id', $session_id)) {
            $sessionInfo = DB::table('sessions')->where('id', $session_id)->first();
            // echo "<pre>";
            // var_dump($sessionInfo);
            // var_dump($id);
            
            // 購入IDを保存する
            $request->session()->put(['product_id' => $id]);
            // echo "</pre>";
            // exit;
            $response = new Response($this->index());
            $response->cookie('product_id', $id, 60 * 24 * 30);
            return $response;
        }        
    }
}
