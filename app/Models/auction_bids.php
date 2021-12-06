<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class auction_bids extends Model
{
    use HasFactory;
    protected $table ='auction_bids';
    protected $fillable = ['id', 'auction_item_id', 'auction_item_type', 'dealer_user_id', 'bid_price', 'created_at', 'updated_at'];



    function get_images(){
        return $this->hasMany('App\Models\trade_car_images', 'sell_car_id', 'auction_item_id');
    }
    
   
    public $timestamps = false;
}
