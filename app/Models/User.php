<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'avatar',
        'email',
        'date_of_birth',
        'mobile',
        'description',
        'category_id',
        'password',
        'is_banned',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = ['Photo'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function socialMedia() {
        return $this->belongsToMany(SocialMedia::class,'user_social_media')
            ->withPivot('followers','url');
    }

    public function getPhotoAttribute() {
        return  $this->avatar ? config('app.url') . Storage::url($this->avatar) : asset('imgs/logo-profile.svg') ;
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }
}
