<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class trade_car_images extends Model
{
    use HasFactory;
    protected $table ='trade_car_images';
    protected $fillable = ['id', 'sell_car_id', 'image_key', 'url', 'thumbnail', 'acv_thumbnail', 'acv_medium', 'acv_large', 'is_primary', 'created_at', 'updated_at'];
   
    public $timestamps = false;
}
