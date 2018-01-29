<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductTableAddMacbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($index = 0; $index < 30; $index++) { 
            $product = new Product();
            $manufactureNum = mt_rand(0, 7);
            $product->category_id = 4;
            $product->product_name = "MacbookPro_201" . $manufactureNum;
            $product->price = 180000;
            $product->stock = mt_rand(10, 50);
            $product->view_flg = 1;
            $product->save();
            
            $lastInsertId = $product->id;
            $data = array(
                'product_id' => $lastInsertId,
                'product_image' => 'macbookpro_201' . $manufactureNum . '.jpg',
                'image_dir' => 'public/image', 
                
            );
            $product->productImages()->create($data);
        }
    }
}
