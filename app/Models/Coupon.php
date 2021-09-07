<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = ['code', 'name', 'coupon_line', 'coupon_amount', 'coupon_type', 'status'];
}
