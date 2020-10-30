<?php

namespace Database\Seeders;

use App\Models\HomePage;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HomePage::create([
            'content' => '<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>',
            'meta_title' => 'home page meta',
            'meta_description'=> 'meta description',
            'image' => 'Images/cda-interview-guide.jpg',
        ]);
    }
}
