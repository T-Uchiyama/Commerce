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
     * カテゴリ情報の取得
     * 
     * @return DB::table('categories') カテゴリ情報
     */
    public function getCategoryData()
    {    
        return DB::table('categories')->pluck('name', 'id');
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
}