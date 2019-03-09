<?php

namespace App\Models;

use App\Models\PasswordReset;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "email", "username", "password",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "password",
    ];

    // /**
    //  * The attributes that should be cast to native types.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     "email_verified_at" => "datetime",
    // ];

    /**
     * Fetch all of the user's filesystems
     */
    public function filesystems()
    {
        return $this->hasMany("App\Models\Filesystem");
    }

    /**
     * Select a user by their email or password
     */
    public static function scopeEmailOrUsername($query, $email, $username)
    {
        return $query->where("email", $email)->orWhere("username", $username);
    }

    /**
     * Generate a new password request for the user
     *
     * @return string
     */
    public function generatePasswordRequest()
    {
        $token = random_str(255);
        $this->passwordResetRequests()->delete();
        PasswordReset::create([
            "user_id" => $this->id,
            "token"   => $token
        ]);
        return $token;
    }

    /**
     * Get the existing password reset requests
     */
    public function passwordResetRequests()
    {
        return $this->hasMany('App\Models\PasswordReset');
    }

    /**
     * Stuff for JWT
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * More stuff for JWT
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
