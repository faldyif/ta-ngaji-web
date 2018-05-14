<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherIdentifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type'       => 'teacher_registeries',
            'id'         => (string)$this->id,
            'attributes'    => [
                'registered_from' => $this->registered_from,
                'minimum_points' => $this->minimum_points,
                'home_short_name' => $this->home_short_name,
                'home_latitude' => $this->home_latitude,
                'home_longitude' => $this->home_longitude,
            ],
        ];
    }
}
