<?php

namespace App\Http\Controllers;

use App\Display;
use DB;
use Session;
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
        /**
         * FIXME:: 現状ではセッションデータのproduct_idとProduct.idが合致した際に
         *         購入済みとなるようにしているがlogoutした際にユーザセッションを全て
         *         破棄するようになっているためlogoutした際でも購入手続きをしたことを保持する必要あり。
         *
         *         また今のプログラムでは単一でしか動作していないので購入履歴を複数個持てるようにも修正要。
         */
        $purchaseNum = Session::get('product_id', '');     
        // TODO:: 今はダイレクトにDB取得しているが既に取得メソッドを記載しているのでそちらから引っ張るようにする。
        $productInfo = DB::table('products')->get();
        return view('/display/index', compact('productInfo', 'purchaseNum'));
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
        $user_id = $request->session()->get('user_id',  \Auth::user()->id);
        // セッションIDの取得
        $session_id = $request->session()->getId();
        
        if (DB::table('sessions')->where('id', $session_id)) {
            $sessionInfo = DB::table('sessions')->where('id', $session_id)->first();
            // echo "<pre>";
            // echo "sessionInfo";
            // var_dump($sessionInfo);
            // echo "ID: ";
            // var_dump($id);
            // echo "Session()->get('product_id') : ";
            // var_dump($request->session()->get('product_id'));
            
            // 購入IDを保存する
            $request->session()->put('product_id', $id);
            // echo "Session()->put('product_id')";
            // var_dump($request->session()->get('product_id'));
            // echo "</pre>";
            // exit;

            //  SessionDBにproduct_idを格納
            //  TODO:: logoutするとDB内の値もリセットされている。
            DB::table('sessions')->where('id', $session_id)->update(['product_id' => $id]);
            $response = new Response($this->index());
            $response->cookie('product_id', $id, 60 * 24 * 30);
            return $response;
        }        
    }
}
