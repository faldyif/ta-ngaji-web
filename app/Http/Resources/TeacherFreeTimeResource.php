<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherFreeTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        PrivateEventsResource::withoutWrapping();
        return [
            'id'            => (string)$this->id,
            'short_place_name' => $this->short_place_name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'distance' => $this->distance,
            'teacher'   => new UserResource($this->user),
            'teacher_rank' => $this->user->teacherRegistery->teacher_level_id,
            'points' => $this->user->teacherRegistery->level->points,
            'total_events' => $this->events->count(),
            'events' => new PrivateEventsResource($this->events),
        ];
    }
}
