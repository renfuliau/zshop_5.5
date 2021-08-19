<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = ['name', 'subject', 'user_id', 'order_id', 'email', 'phone', 'message', 'read_at'];
}
