<?php

use Illuminate\Database\Seeder;

class TeacherCurriculumCompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teacher_curriculum_competences')->insert(['teacher_curriculum_id' => 1, 'competence' => "Mengenal ilmu Tajwid, Membaca dengan baik, Basmalah dan isti'adhah", 'subject_matter' => "Pengertian Tajwid, Hukum dan tujuan mempelajarinya, Cara membaca isti'adhah", 'time_required' => 60]);
    }
}
