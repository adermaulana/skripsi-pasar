<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data=array(
            array(
                'name'=>'Admin',
                'email'=>'admin@gmail.com',
                'password'=>Hash::make('admin'),
                'role'=>'admin',
                'status'=>'active'
            ),
            array(
                'name'=>'User1',
                'email'=>'user1@gmail.com',
                'password'=>Hash::make('12345'),
                'role'=>'user',
                'status'=>'active'
            ),
        );

        DB::table('products')->insert($data);
    }
}
