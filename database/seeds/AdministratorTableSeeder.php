<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdministratorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('administrators')->insert([
            [
                'name'       => 'administrator',
                'email'      => 't_uchiyama@funteam.co.jp',
                'password'   => bcrypt('administrator'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'uchiyat',
                'email'      => 'acm.lock.apocalypse@gmail.com',
                'password'   => bcrypt('uchiyat'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
