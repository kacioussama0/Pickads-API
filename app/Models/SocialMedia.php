<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'url',
        'logo'
    ];

    public function user() {
        return $this->belongsToMany(User::class,'user_social_media')
            ->withPivot('followers','url');
    }
}
