<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class trade_your_car extends Model
{
    use HasFactory;
    protected $table ='trade_your_car';
    protected $fillable = ['vin', 'odometer', 'transmission', 'trim', 'drivetrain', 'engine', 'fuel_type', 'year', 'make', 'model', 'color', 'user_id', 'body_type', 'condition', 'exterior_color', 'state', 'city', 'zip', 'phone', 'vehicle_driving', 'transmission_issue', 'drivetrain_issue', 'steering_issue', 'brake_issue', 'suspension_issue', 'minor_body_damage', 'moderate_body_damage', 'major_body_damage', 'scratches', 'glass_damaged_cracked', 'lights_damaged_cracked', 'minor_body_rust', 'moderate_body_rust', 'major_body_rust', 'aftermarket_parts_exterior', 'mismatched_paint_colors', 'previous_paint_work', 'seat_damage', 'carpet_damage', 'dashboard_damage', 'interior_trim_damage', 'sunroof', 'navigation', 'aftermarket_stereo_equipment', 'hvac_not_working', 'leather_Or_Leather_type_seats', 'shoping_make', 'shoping_model', 'radius', 'loan_or_lease_on_car', 'car_keys', 'reserve_price', 'tradersell_inspected', 'latitude', 'longitude', 'type', 'status', 'publish_status'];

    public $timestamps = false;

    function get_images(){
        return $this->hasMany('App\Models\trade_car_images', 'sell_car_id', 'id');
    }

    function auction_bids() {
        return $this->hasMany('App\Models\auction_bids', 'auction_item_id', 'id');
    }

    function auction_bid_with_max_price() {
        return $this->hasOne('App\Models\auction_bids', 'auction_item_id', 'id')->orderBy('bid_price', 'DESC');
    }

}
