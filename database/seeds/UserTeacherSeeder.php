<?php

use Illuminate\Database\Seeder;

class UserTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 25)->create([
            'role_id' => 2,
        ])->each(function ($u) {
            $u->teacherRegistery()->save(factory(\App\TeacherRegistery::class)->make());
            $u->linked_id = $u->teacherRegistery->id;
            $u->save();
        });
    }
}
