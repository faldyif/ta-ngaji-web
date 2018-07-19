<?php

use App\User;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator NgajiYuk',
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
            'whatsapp_number' => '+62888888888',
            'gender' => 'M',
            'role_id' => 3,
            'verified' => true,
        ]);
    }
}
