<?php

use Illuminate\Database\Seeder;

class TeacherFreeTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\TeacherFreeTime::class, 50)->create();
    }
}
