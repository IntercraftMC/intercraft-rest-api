<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filesystem extends Model
{
    /**
     * Mass fillable columns
     *
     * @var array
     */
    protected $fillable = [
        "user_id", "is_creative", "uuid"
    ];

    /**
     * Fetch the user who owns this filesystem
     */
    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }
}
