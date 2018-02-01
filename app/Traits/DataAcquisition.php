<?php

namespace App\Traits;

use DB;
use App\Product;    
use Illuminate\Http\Request;

trait DataAcquisition
{
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
    
    /**
     * 商品DBよりページネーション結果取得
     *
     * @param $option 取得アイテム数
     *
     * @return List ProductInfo
     */
    public function getProductPaginate($option = 10)
    {
        $productInfo = DB::table('products')->paginate($option);
        foreach ($productInfo as $product) {
            $product_id = $product->id;
            // サムネイル表示は一件のみで問題ないためFirstで取得
            $productImageInfo = \App\Product::find($product_id)->productImages()->first();
            $product->product_image = $productImageInfo->product_image;
        }

        return $productInfo;
    }
    
    /**
     * カテゴリ情報の取得
     * @param $id カテゴリID
     * 
     * @return DB::table('categories') カテゴリ情報
     */
    public function getCategoryData($id = 0)
    {
        if ($id == 0) {
            return DB::table('categories')->pluck('name', 'id');
        } else {
            return DB::table('categories')->where('id', $id)->first();
        } 
    }
    
    /**
     * ユーザー情報を全件取得
     * 
     * @return DB::table('users')
     */
    public function getUserMasterData()
    {
        return DB::table('users')->get();
    }
    
    /**
     * カテゴリ情報を全件取得
     * 
     * @return DB::table('categories')
     */
    public function getCategoryMasterData()
    {
        return DB::table('categories')->get();
    }
    
    /**
     * 商品情報を全件取得
     * 
     * @return DB::table('products')
     */
    public function getProductMasterData()
    {
        $productMasterData = DB::table('products')->get();

        foreach ($productMasterData as $key => $value) {
            $productMasterData[$key]->product_image = \App\Product::find($value->id)->productImages()->first()->product_image;
        }
        
        return $productMasterData;
    }
    
    /**
     * 検索文字を取得しクエリを用い検索結果の表示
     * @param  $category_id カテゴリID
     * @param  $search_Text 検索文字列
     * @param  $controller メソッド呼び出し元のコントローラー名 
     * 
     * @return \Illuminate\Http\Response
     */
    public function getQuerySearch($category_id = 0, $search_Text = null, $controller = null)
    {    
        if ($controller == 'Product') {
            $option = 20;
        } else {
            $option = 40;
        }
        $paginate_flg = false;
        if (!empty($category_id) && !empty($search_Text)) {
            $productInfo = \App\Product::where('category_id', $category_id)
                                        ->orWhere('product_name', 'LIKE', "%$search_Text%")
                                        ->paginate($option);
            $paginate_flg = true;
        } else if (!empty($category_id) && empty($search_Text)) {
            $productInfo = \App\Product::where('category_id', $category_id)
                                        ->paginate($option);
            $paginate_flg = true;
        } else if (empty($category_id) && !empty($search_Text)) {
            $productInfo = \App\Product::where('product_name', 'LIKE', "%$search_Text%")
                                        ->paginate($option);
            $paginate_flg = true;
        }
        
        if ($paginate_flg) {
            foreach ($productInfo as $key => $value) {
                $productInfo[$key]->product_image = \App\Product::find($value->id)->productImages()->first()->product_image; 
            }
        } else {
            $productInfo = $this->getProductPaginate($option);
        }
        return $productInfo;
    }
}