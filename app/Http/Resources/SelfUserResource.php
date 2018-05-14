<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SelfUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => (string)$this->id,
            'name' => $this->name,
            'email' => $this->email,
            'whatsapp_number' => $this->whatsapp_number,
            'gender' => $this->gender(),
            'role' => $this->role(),
            'credits_amount' => $this->credits_amount,
            'loyalty_points' => $this->loyalty_points,
        ];

        // Check if the user is a teacher
        if($response['role'] == 'teacher') {

            $response['teacher_data'] = new TeacherResource($this->teacherRegistery);

            $eventsData = [];
            $eventsData['total_hosted'] = $this->teacherRegistery->events->count();

            $response['events_data'] = $eventsData;
        }

        return $response;
    }
}
