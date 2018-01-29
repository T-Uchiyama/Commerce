<?php

namespace App\Http\Controllers;

use DB;
use App\Product;  
use App\Traits\DataAcquisition;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HomeController extends Controller
{
    use DataAcquisition;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function userInfo()
    {
        $userMasterData = $this->getUserMasterData();
        return view('admin.user', compact('userMasterData'));
    }
    
    public function productInfo()
    {
        $productMasterData = $this->getProductMasterData();
        return view('admin.product', compact('productMasterData'));
    }
    
    /**
     * 管理者画面側の商品編集画面の表示
     * 
     * @param  integer $id 商品ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function showProductEditForm($id = 0)
    {
        $product = $this->getProductList($id);

        return view('admin.productEdit', compact('product'));
    }
    
    /**
     * 管理者画面側での商品情報修正
     * 
     * @param Request $request リクエストデータ
     * @param integer $id      商品ID
     *
     * @return \Illuminate\Http\Response
     */
    public function ProductMasterEdit(Request $request, $id = 0)
    {
        $this->validate($request, [
            'productName' => 'required',
            'price'       => 'numeric|required',
            'stock'       => 'numeric|required',
        ]);
        
        $product = \App\Product::find($id);
        $product->product_name = $request->productName;
        $product->price = $request->price;
        $product->stock = $request->stock;
        
        if($product->save()) {
            return redirect('product_info')->with('status', '編集しました。'); 
        } else {
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['edit' => '保存に失敗しました。']);
        }
    }
    
    /**
     * 商品情報をCSV形式でエクスポート
     * 
     * @return Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadCSV()
    {
        return  new StreamedResponse(
            function () {
                $stream = fopen('php://output', 'w');
                DB::table('products')->orderBy('id')->chunk(100, function ($products) use ($stream) {
                    foreach ($products as $product) {
                        fputcsv($stream, [
                            $product->id,
                            $product->category_id, 
                            $product->product_name,
                            $product->price, 
                            $product->stock
                        ]);
                    }
                });
                fclose($stream);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="productAll.csv"',
            ]
        );
    }
}
