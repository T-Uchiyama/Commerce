<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/product/upload');
    }
    
    /**
     * ファイルアップロード処理
     * @param  Request $request リクエストデータ
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => [
                   'required',
                   'file',
               ]
        ]);
        
        if ($request->file('file')->isValid([])) {
             $filename = $request->file->store('public/image');

             $product->product_image = basename($filename);
             $product->save();
             
             return redirect('/product.upload')->with('success', '保存しました。');
         } else {
             return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['file' => '画像がアップロードされていないか不正なデータです。']);
         }
    }
}
