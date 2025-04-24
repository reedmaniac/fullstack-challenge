<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'email_verified_at' => $this->email_verified_at->toIso8601String(),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'current_weather' => json_decode($this->current_weather),
            'current_weather_last_updated_at' => $this->current_weather_last_updated_at?->toIso8601String(),
        ];
    }
}
