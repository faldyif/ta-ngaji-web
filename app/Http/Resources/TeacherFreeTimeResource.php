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
            'fixed_place' => $this->fixed_place,
            'short_place_name' => $this->short_place_name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'teacher'   => new UserResource($this->user),
            'events' => new PrivateEventsResource($this->events($this->start_time, $this->end_time)),
        ];
    }
}
