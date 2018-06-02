<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $genderPrefix = "";

//        if($this->role_id == 2) {
//            if($this->gender == 'M') {
//                $genderPrefix = "Ustadz ";
//            } else {
//                $genderPrefix = "Ustadzah ";
//            }
//        }

        return [
            'id' => (string)$this->id,
            'name' => $genderPrefix . $this->name,
            'whatsapp_number' => $this->whatsapp_number,
            'profile_pic_url' => url('storage/temp/' . $this->profile_pic_path),
        ];
    }
}
