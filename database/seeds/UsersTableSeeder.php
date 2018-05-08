<?php

use App\User;
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
        factory(App\User::class, 50)->create();

        User::create([
            'name' => 'Faldy Ikhwan Fadila',
            'email' => 'faldy.if@gmail.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
            'whatsapp_number' => '+6287889022592',
            'gender' => 'M',
            'role_id' => 1,
            'verified' => true,
        ]);
    }
}