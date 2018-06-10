<?php

use Illuminate\Database\Seeder;

class TeacherCurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teacher_curriculums')->insert(['curriculum_name' => "Panduan Pembelajaran Buku Tajwid Metode Asy-Syafi'i", 'time_total' => 1200]);
    }
}
