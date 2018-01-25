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
}