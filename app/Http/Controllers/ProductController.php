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
     * 編集画面へ遷移
     *
     * @param  $id 商品ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id = 0)
    {
        $product = $this->getProductList($id);

        return view('products.edit', compact('product'));
    }
    
    /**
     * ファイルアップロード処理
     * @param  Request $request リクエストデータ
     */
    public function upload(Request $request)
    {
        if (!Auth::check()) {
            return view('register.index')->withErrors(['notLogin' => '商品登録のためにはログインしてください']);
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
             
             return redirect('/product')->with('status', '保存しました。');
         } else {
             return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['file' => '画像がアップロードされていないか不正なデータです。']);
         }
    }
    
    /**
     * 商品情報を修正する
     * 
     * @param  Request $request リクエストデータ
     * @param  integer $id      商品ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id = 0)
    {
        if (!Auth::check()) {
            return view('register.index')->withErrors(['notLogin' => '商品情報編集のためにはログインしてください']);
        }
        
        $product = \App\Product::find($id);
        $product->price = $request->price;
        $product->stock = $request->stock;
        
        if($product->save()) {
            return redirect('product')->with('status', '編集しました。'); 
        } else {
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['edit' => '保存に失敗しました。']);
        }
    }
    
    /**
     * 商品リスト全件取得
     *
     * @param $id 商品ID
     * 
     * @return List ProductInfo
     */
    public function getProductList($id = 0)
    {
        if ($id == 0) {
            $productInfo = DB::table('products')->get();
            foreach ($productInfo as $product) {
                $product_id = $product->id;
                // サムネイル表示は一件のみで問題ないためFirstで取得
                $productImageInfo = \App\Product::find($product_id)->productImages()->first();
                $product->product_image = $productImageInfo->product_image;
            }
        } else {
            $productInfo = DB::table('products')->where('id', $id)->first();
            $productInfo->product_image = \App\Product::find($id)->productImages()->first()->product_image;                                                 
        }

        return $productInfo;
    }
    
    public function getCategoryData()
    {
        $categoryList = DB::table('categories')->pluck('name', 'id');
        
        return $categoryList;
    }
}
