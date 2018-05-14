<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRelationshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // create blank array for relationship usage
        $relationships = [];

        // add teacher relationship if selected user is a teacher
        if($this->role() == 'teacher') {

            $teacher_identity = [
                'links' => [
                    'self' => route('api.user.profile.relationships.teacher'),
                    'related' => route('api.user.profile'),
                ],
                'data' => new TeacherIdentifierResource($this->teacherRegistery),
            ];

            array_add($relationships, 'teacher_identity', $teacher_identity);

        }



        return $relationships;
    }
}
