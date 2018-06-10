<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'id'            => (string)$this->id,
            'event_type' => $this->eventType(),
            'short_place_name' => $this->short_place_name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'location_details' => $this->location_details,
            'points_offered' => $this->teacher->level->points,
            'teacher_rank' => $this->user->teacherRegistery->teacher_level_id,
            'teacher'   => new UserResource($this->user),
            'student'   => new UserResource($this->student),
        ];
    }
}
