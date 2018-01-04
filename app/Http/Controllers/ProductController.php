<?php

namespace App\Http\Controllers;

use DB;
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
        $products = $this->getImageList();
        return view('/products/upload', compact('products'));
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
             $filename = $request->file->storeAs('public/image', $request->file->getClientOriginalName());
             $product = new Product();
             $product->product_image = basename($filename);
             $product->save();
             
             return redirect('/product')->with('success', '保存しました。');
         } else {
             return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['file' => '画像がアップロードされていないか不正なデータです。']);
         }
    }
    
    /**
     * 商品イメージリスト全件取得
     * @return List ImageInfo
     */
    public function getImageList()
    {
        $imageInfo = DB::table('products')->get();
        
        return $imageInfo;
    }
}
