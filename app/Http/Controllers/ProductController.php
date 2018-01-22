<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->getProductList();
        $categoryList = $this->getCategoryData();

        return view('products.upload', compact('products', 'categoryList'));
    }
    
    /**
     * ファイルアップロード処理
     * @param  Request $request リクエストデータ
     */
    public function upload(Request $request)
    {
        if (!Auth::check()) {
            return view('register.index')->withErrors(['notLogin' => 'ログインしてください']);
        }
        
        $this->validate($request, [
            'file[]' => [
                'file',
            ],
            'productName' => 'required',
            'category_id' => 'required',
            'price'       => 'numeric|required',
            'stock'       => 'numeric|required',
        ]);

        $fileData = array();

        foreach ($request->file('file') as $file) {
            if ($file->isValid()) {
                $filename = $file->storeAs('public/image', $file->getClientOriginalName());
                $fileData[] = array(
                    'product_image' => basename($filename),
                    'image_dir' => 'public/image',
                );
            }
        }

        if (count($fileData) > 0) {
            $product = new Product();
            $product->product_name = $request->productName;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->view_flg = true;
            $product->save();
            $lastInsertId = $product->id;
            foreach ($fileData as $data) {
                
                $data = array_merge($data, array('product_id' => $lastInsertId));
                $product->productImages()->create($data);
            }
             
             return redirect('/product')->with('success', '保存しました。');
         } else {
             return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['file' => '画像がアップロードされていないか不正なデータです。']);
         }
    }
    
    /**
     * 商品リスト全件取得
     * @return List ProductInfo
     */
    public function getProductList()
    {
        $productInfo = DB::table('products')->get();
        foreach ($productInfo as $product) {
            $product_id = $product->id;
            // サムネイル表示は一件のみで問題ないためFirstで取得
            $productImageInfo = \App\Product::find($product_id)->productImages()->first();
            $product->product_image = $productImageInfo->product_image;
        }

        return $productInfo;
    }
    
    public function getCategoryData()
    {
        $categoryList = DB::table('categories')->pluck('name', 'id');
        
        return $categoryList;
    }
}
