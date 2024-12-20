<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function postComments(){
        return $this->hasManyThrough(Comment::class, Post::class);
    }
    public function postLikes(){
        return $this->hasManyThrough(Like::class, Post::class);
    }
    public function followers(){
        return $this->hasManyThrough(User::class, Follow::class, 'followee_id', 'id', 'id', 'follower_id');
    }
    public function followees(){
        return $this->hasManyThrough(User::class, Follow::class, 'follower_id', 'id', 'id', 'followee_id');
    }
    public function authHasFollowed(): Attribute {
        return Attribute::get(function (){
            if(!Auth::check()){
                return false;
            }
            return $this->followers()->where('follows.follower_id', Auth::id())->exists();
        });
    }
}
