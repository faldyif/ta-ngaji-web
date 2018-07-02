<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(TeacherLevelSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(UserTeacherSeeder::class);
//         $this->call(EventSeeder::class);
         $this->call(TeacherFreeTimeSeeder::class);
    }
}
