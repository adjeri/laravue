<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create(['email' => 'adminpanel@cdainterview.test']);
        $admin->givePermissionTo(['ViewHome', 'ViewPanel']);

        $guest = User::factory()->create(['email' => 'homepage@cdainterview.test']);
        $guest->givePermissionTo('ViewHome');
    }
}
