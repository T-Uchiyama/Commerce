<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductTableAddLadMusicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($index = 0; $index < 50; $index++) { 
            $product = new Product();
            $manufactureNum = mt_rand(1, 10);
            $product->category_id = 1;
            $product->product_name = "LadMusician_" . $manufactureNum;
            $product->price = 15000;
            $product->stock = mt_rand(1, 5);
            $product->view_flg = 1;
            $product->save();
            
            $lastInsertId = $product->id;
            $data = array(
                'product_id' => $lastInsertId,
                'product_image' => 'lad_musician_' . $manufactureNum . '.jpg',
                'image_dir' => 'public/image', 
                
            );
            $product->productImages()->create($data);
        }
    }
}
