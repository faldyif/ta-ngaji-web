<?php

use Illuminate\Database\Seeder;

class TeacherLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teacher_levels')->insert(['name' => "Bronze", 'points' => '10']);
        DB::table('teacher_levels')->insert(['name' => "Silver", 'points' => '-20']);
        DB::table('teacher_levels')->insert(['name' => "Gold", 'points' => '-100']);
//        DB::table('teacher_levels')->insert(['name' => "Platinum", 'points' => '-500']);
    }
}
