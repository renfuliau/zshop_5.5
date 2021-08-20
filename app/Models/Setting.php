<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['short_des', 'description', 'photo', 'address', 'phone', 'email', 'logo'];

    public static function getPhoto()
    {
        return Setting::where('id', 1)->first();
    }
}