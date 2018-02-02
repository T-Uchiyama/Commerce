<?php

namespace App\Http\Controllers;

use DB;
use App\Product;  
use App\Category;  
use App\Traits\DataAcquisition;
use Illuminate\Http\Request;
use \SplFileObject;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;

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
    
    public function categoryInfo()
    {
        $categoryMasterData = $this->getCategoryMasterData();
        return view('admin.category', compact('categoryMasterData'));
    }
    
    public function orderInfo()
    {
        $orderMasterData = $this->getOrderMasterData();
        return view('admin.order', compact('orderMasterData'));
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
     * 管理者画面側のカテゴリ新規追加画面の表示
     * 
     * @return \Illuminate\Http\Response
     */
    public function showCategoryCreateForm()
    {
        return view('admin.categoryCreate');
    }
    
    /**
     * 管理者画面側のカテゴリ編集画面の表示
     * 
     * @param  integer $id 商品ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function showCategoryEditForm($id = 0)
    {
        $category = $this->getCategoryData($id);

        return view('admin.categoryEdit', compact('category'));
    }
    
    /**
     * 管理者画面側での商品情報修正
     * 
     * @param Request $request リクエストデータ
     * @param integer $id      商品ID
     *
     * @return \Illuminate\Http\Response
     */
    public function productMasterEdit(Request $request, $id = 0)
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
     * 管理者画面側でのカテゴリ情報新規作成
     * 
     * @param Request $request リクエストデータ
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryMasterCreate(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
        ]);
        
        $category = new Category();
        $category->name = $request->categoryName;
        
        if($category->save()) {
            return redirect('category_info')->with('status', '追加しました。'); 
        } else {
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['create' => '新規作成に失敗しました。']);
        }
    }
    
    /**
     * 管理者画面側でのカテゴリ情報修正
     * 
     * @param Request $request リクエストデータ
     * @param integer $id      商品ID
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryMasterEdit(Request $request, $id = 0)
    {
        $this->validate($request, [
            'categoryName' => 'required',
        ]);
        
        $category = \App\Category::find($id);
        $category->name = $request->categoryName;
        
        if($category->save()) {
            return redirect('category_info')->with('status', '編集しました。'); 
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
        return new StreamedResponse(
            function () {
                $stream = fopen('php://output', 'w');
                // カラム名を内容の記載前にヘッダーとして追加
                fputcsv($stream, ["id", "category_id", "product_name", "price", "stock", "product_image"]);
                
                DB::table('products')->orderBy('id')->chunk(100, function ($products) use ($stream) {
                    foreach ($products as $product) {
                        $product_image = '';
                        if (count(\App\Product::find($product->id)->productImages()->get()) > 1) {
                            $counter = 1;
                            foreach (\App\Product::find($product->id)->productImages()->get() as $imageData) {
                                $product_image = $product_image . $imageData->product_image;
                                if ($counter < count(\App\Product::find(1)->productImages()->get())) {
                                    $product_image = $product_image . ",";
                                }
                                $counter++;
                            } 
                        } else {
                            $product_image = \App\Product::find($product->id)->productImages()->first()->product_image;
                        }
                        fputcsv($stream, [
                            $product->id,
                            $product->category_id, 
                            $product->product_name,
                            $product->price, 
                            $product->stock,
                            $product_image,
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
    
    /**
     * CSVインポート画面の表示
     *
     * @return \Illuminate\Http\Response
     */
    public function showInportForm()
    {
        return view('admin.import');
    }
    
    /**
     * アップロードされたCSVを読み込み商品DBへインポート
     * 
     * @param  Request $request CSVファイル
     * 
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => [
                'required',
                'file'
            ]
        ]);
        
        if ($request->file('file')->isValid([])) {
            $filename = $request->file->storeAs('public/csv', $request->file->getClientOriginalName());
            $filePath = 'storage/csv/'.basename($filename);
            $file = new SplFileObject($filePath); 
            $file->setFlags(SplFileObject::READ_CSV); 
            $csvData = array();
            
            $lineCount = 1;
            // CSVデータを取得
            foreach ($file as $key => $line) {
                $tmp_array = array();
                $set_flg = false;
                
                //最初の行にはカラム名が記載されているため行を飛ばす。
                if ($lineCount > 1) {
                    foreach($line as $str) {
                        // エンコードをすべてUTF-8に変換
                        mb_language("Japanese");
                        $str = mb_convert_encoding($str, "UTF-8", "auto");
                        $tmp_array[] = htmlspecialchars($str);
                    }
                    // 空行かどうかチェック
                    foreach($tmp_array as $tmp) {
                        if(!empty($tmp)) {
                            $set_flg = true;
                        }
                    }
                    // 空行でなければ登録する
                    if($set_flg){
                         $csvData[] = $tmp_array;
                    }   
                }
                $lineCount++;
            } 

            // 取得したCSV情報をバラし、DBに格納
            foreach ($csvData as $line) {
                $lastInsertId = DB::table('products')->insertGetId([
                    'category_id' => $line[1],
                    'product_name' => $line[2],
                    'price' => $line[3],
                    'stock' => $line[4],
                    'view_flg' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
                
                if($lastInsertId) {
                    if (preg_match('/,/', $line[5])) {
                        $imageNameArr = explode(',', $line[5]);
                        
                        foreach ($imageNameArr as $imageName) {
                            DB::table('product_images')->insert([
                                'product_id' => $lastInsertId, 
                                'product_image' => $imageName,
                                'image_dir' => 'public/image',
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            ]);
                        }
                    } else {
                        DB::table('product_images')->insert([
                            'product_id' => $lastInsertId, 
                            'product_image' => $line[5],
                            'image_dir' => 'public/image',
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
            return redirect('import')->with('status', 'CSV Import Success!'); 
        } else {
            return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(['file' => '画像がアップロードされていないか不正なデータです。']);
        }
    }

    /**
     * PDF形式で領収書生成
     *
     * @param $id 注文ID
     * 
     * @return PDF
     */
    public function generatePDF($id = 0)
    {
        if (!Auth::guard('admin')->check()) {
            $user = '○○ ○○';
        } else {
            $user = Auth::guard('admin')->user()->name;    
        }
        $order = $this->getOrderMasterData($id);
        $content = explode("\n", $order->purchase_content);
        $pdf = \PDF::loadView('pdf.format', compact('user', 'content'));
        return $pdf->stream('receipt.pdf');
    }
}
