<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          "full_name" => $this->last_name . ' ' . $this->first_name,
          "description" => $this->description,
          "username" => $this->username,
          "email" => $this->email,
          "mobile" => $this->mobile,
          "date_of_birth" => $this->date_of_birth,
          "category" => $this->category->name,
          "gender" => $this->gender,
          "avatar"  => $this->avatar
        ];
    }
}
