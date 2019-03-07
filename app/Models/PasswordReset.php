<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    /**
     * Do not use timestamps
     *
     * @var boolean
     */
    public $timestamps = False;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id", "token"
    ];

    /**
     * Fetch a password reset item if an active one exists
     */
    public static function activeRequest($token)
    {
        $timestamp = Carbon::now()->subDays(1);
        return self::where("token", $token)->first();
    }

    /**
     * Fetch the user this reset request belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
