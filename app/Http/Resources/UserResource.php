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
          "id" => $this->id,
          "first_name" => $this->first_name,
          "last_name" => $this->last_name,
          "bio" => $this->description,
          "username" => $this->username,
          "email" => $this->email,
          "category" => [
              "id" => $this->category->id,
              "name" => $this->category->name,
          ],
          "dob" => $this->date_of_birth,
          "gender" => $this->gender,
          "mobile" => $this->mobile,
          "avatar"  => [
              'original' => $this->photo,
              'small' => str_replace('original','small',$this->photo),
              'medium' => str_replace('original','medium',$this->photo),
              'large' => str_replace('original','large',$this->photo),
          ],
          "social_medias"  => $this->socialMedia()->orderBy('followers','DESC')->get(),
          "banned"  => $this->is_banned,
           "likes" => $this->likes->count()
        ];

    }
}
