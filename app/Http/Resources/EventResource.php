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
            'is_available' => $this->is_available,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'points_offered' => $this->points_offered,
            'teacher'   => new UserResource($this->user()),
        ];
    }
}
