<?php

namespace App\Http\Controllers;

use League\Flysystem\Config;

use Illuminate\Http\Request;

use App\Mail\Auction;

use App\Mail\Message;

use App\Mail\Bid;

use App\Models\trade_your_car;

use App\Models\auction_bids;

use App\Models\trade_car_images;

use App\Models\messaging;

use App\Models\User;

use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Validator;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

use DB;
use PhpParser\Node\Expr\Cast\Object_;

class Trade_Your_CarController extends Controller
{
    //
    function add(Request $req){
        $car   = new trade_your_car;
        $car->vin=$req->input('vin');
        $car->odometer=$req->input('odometer');
        $car->transmission=$req->input('transmission');
        $car->trim=$req->input('trim');
        $car->drivetrain=$req->input('drivetrain');
      	$car->mileage=$req->input('odometer');
        $car->engine=$req->input('engine');
        $car->fuel_type=$req->input('fuel_type');
        $car->year=$req->input('year');
        $car->make=$req->input('make');
        $car->model=$req->input('model');
        $car->color=$req->input('color');
        $car->user_id=auth()->user()->id;
        $car->body_type=$req->input('body_type');
        $car->condition=$req->input('condition');
        $car->exterior_color=$req->input('exterior_color');
        $car->state=$req->input('state');
        $car->city=$req->input('city');
        $car->zip=$req->input('zip');
        $car->phone=$req->input('phone');
        $car->vehicle_driving=$req->input('vehicle_driving');
        $car->transmission_issue=$req->input('transmission_issue');
        $car->drivetrain_issue=$req->input('drivetrain_issue');
        $car->steering_issue=$req->input('steering_issue');
        $car->brake_issue=$req->input('brake_issue');
        $car->suspension_issue=$req->input('suspension_issue');
        $car->minor_body_damage=$req->input('minor_body_damage');
        $car->moderate_body_damage=$req->input('moderate_body_damage');
        $car->major_body_damage=$req->input('major_body_damage');
        $car->scratches=$req->input('scratches');
        $car->glass_damaged_cracked=$req->input('glass_damaged_cracked');
        $car->lights_damaged_cracked=$req->input('lights_damaged_cracked');
        $car->minor_body_rust=$req->input('minor_body_rust');
        $car->moderate_body_rust=$req->input('moderate_body_rust');
        $car->major_body_rust=$req->input('major_body_rust');
        $car->aftermarket_parts_exterior=$req->input('aftermarket_parts_exterior');
        $car->mismatched_paint_colors=$req->input('mismatched_paint_colors');
        $car->previous_paint_work=$req->input('previous_paint_work');
        $car->seat_damage=$req->input('seat_damage');
        $car->carpet_damage=$req->input('carpet_damage');
        $car->dashboard_damage=$req->input('dashboard_damage');
        $car->interior_trim_damage=$req->input('interior_trim_damage');
        $car->sunroof=$req->input('sunroof');
        $car->navigation=$req->input('navigation');
        $car->aftermarket_stereo_equipment=$req->input('aftermarket_stereo_equipment');
        $car->hvac_not_working=$req->input('hvac_not_working');
        $car->leather_Or_Leather_type_seats=$req->input('leather_Or_Leather_type_seats');
        $car->shoping_make=$req->input('shoping_make');
        $car->shoping_model=$req->input('shoping_model');
        $car->radius=$req->input('radius');
        $car->loan_or_lease_on_car=$req->input('loan_or_lease_on_car');
        $car->car_keys=$req->input('car_keys');
        $car->reserve_price=$req->input('reserve_price');
        $car->tradersell_inspected=$req->input('tradersell_inspected');
        $car->latitude=$req->input('latitude');
        $car->longitude=$req->input('longitude');
        $car->type=$req->input('type');
        $car->status="Pending";
        $car->publish_status=$req->input('publish_status');
        $car->expiry_at=date("Y-m-d H:i:s", strtotime('+1 week'));

        if ($req->input('id')) {
           $data = $req->input();
           $id = $req->input('id');
           $gift = trade_your_car::find($id);
           $gift->fill($data);
           $gift->save();
           $upMileage = trade_your_car::where('id', $id)->update(array('mileage' => $req->input('mileage')));
           $sell_id = $id;
        } else {
            $car->save();
            $sell_id = $car->id;
        }

        $allowedfileExtension=['jpg','png','JPG','PNG','jpeg','JPEG'];

        /// starting front_Seats Image Upload
        if ($req->hasFile('front_Seats')) {
            if ($req->input('id')) {
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','2')->delete();
            }

            $files = $req->file('front_Seats');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if ($check) {
                    foreach ($req->front_Seats as $mediaFiles) {
                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_front_Seats= new trade_car_images();
                        $save_front_Seats->image_key = $name;
                        $save_front_Seats->url = $file_name;
                        $save_front_Seats->sell_car_id = $sell_id;
                        $save_front_Seats->is_primary = 2;
                        $save_front_Seats->save();
                    }
                }

            }

        }

        /// starting dash Image Upload
        if ($req->hasFile('dash')) {
            if ($req->input('id')) {
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','3')->delete();
            }

            $files = $req->file('dash');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->dash as $mediaFiles) {
                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_dash = new trade_car_images();
                        $save_dash->image_key = $name;
                        $save_dash->url = $file_name;
                        $save_dash->sell_car_id = $sell_id;
                        $save_dash->is_primary = 3;
                        $save_dash->save();
                    }
                }
            }

        }
        /// ending dash Image Upload

        /// starting navigation Image Upload
        if($req->hasFile('navigation')) {
            if($req->input('id')){
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','4')->delete();
            }
           // return response()->json(['message' => "OK", 'status' => '200'], 200);

            $files = $req->file('navigation');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->navigation as $mediaFiles) {

                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_navigation = new trade_car_images();
                        $save_navigation->image_key = $name;
                        $save_navigation->url = $file_name;
                        $save_navigation->sell_car_id = $sell_id;
                        $save_navigation->is_primary = 4;
                        $save_navigation->save();
                    }
                }

                //return response()->json(['file_uploaded'], 200);

            }
        }
        /// ending dash Image Upload


        /// starting front Image Upload
        if($req->hasFile('front')) {
            if($req->input('id')){
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','5')->delete();
            }

            $files = $req->file('front');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->front as $mediaFiles) {

                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_front = new trade_car_images();
                        $save_front->image_key = $name;
                        $save_front->url = $file_name;
                        $save_front->sell_car_id = $sell_id;
                        $save_front->is_primary = 5;
                        $save_front->save();
                    }
                }
                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending front Image Upload


        /// starting rear Image Upload
        if($req->hasFile('rear')) {
            if($req->input('id')){
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','6')->delete();
            }

            $files = $req->file('rear');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->rear as $mediaFiles) {

                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_rear = new trade_car_images();
                        $save_rear->image_key = $name;
                        $save_rear->url = $file_name;
                        $save_rear->sell_car_id = $sell_id;
                        $save_rear->is_primary = 6;
                        $save_rear->save();
                    }
                }
                //return response()->json(['file_uploaded'], 200);
            }

        }
        /// ending rear Image Upload





          /// starting driver_s_side Image Upload
        if($req->hasFile('driver_s_side')) {
            if($req->input('id')){
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','7')->delete();
            }

            $files = $req->file('driver_s_side');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->driver_s_side as $mediaFiles) {
                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_driver_s_side = new trade_car_images();
                        $save_driver_s_side->image_key = $name;
                        $save_driver_s_side->url = $file_name;
                        $save_driver_s_side->sell_car_id = $sell_id;
                        $save_driver_s_side->is_primary = 7;
                        $save_driver_s_side->save();
                    }
                }
            }

        }
        /// ending driver_s_side Image Upload

        /// starting driver_s_side Image Upload
        if($req->hasFile('passenger_s_side')) {
            if($req->input('id')){
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','8')->delete();
            }
           // return response()->json(['message' => "OK", 'status' => '200'], 200);

            $files = $req->file('passenger_s_side');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->passenger_s_side as $mediaFiles) {
                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_passenger_s_side = new trade_car_images();
                        $save_passenger_s_side->image_key = $name;
                        $save_passenger_s_side->url = $file_name;
                        $save_passenger_s_side->sell_car_id = $sell_id;
                        $save_passenger_s_side->is_primary = 8;
                        $save_passenger_s_side->save();
                    }
                }
                //return response()->json(['file_uploaded'], 200);

            }
        }
        /// ending passenger_s_side Image Upload

        /// starting Image Upload
        if($req->hasFile('additional_photos')) {
            if($req->input('id')){
                $id = $req->input('id');
                trade_car_images::where('sell_car_id',$id)->where('is_primary','0')->delete();
            }
           // return response()->json(['message' => "OK", 'status' => '200'], 200);

            $files = $req->file('additional_photos');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->additional_photos as $mediaFiles) {

                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save = new trade_car_images();
                        $save->image_key = $name;
                        $save->url = $file_name;
                        $save->sell_car_id = $sell_id;
                        $save->save();
                    }
                } else {
                    return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending Image Upload

        //return response()->json(['file_uploaded'], 200);

        // Check draft or published and send email notification if published
        if ($car->publish_status && $car->publish_status != "draft") {
            $user = auth()->user();
            Mail::to($user)->send(new Auction(
                $user->name,
                $car->publish_status,
                array(
                    'year' => $car->year,
                    'mileage' => $car->mileage,
                    'make' => $car->make,
                    'model' => $car->model
                )
            ));
        }

        if ($req->input('id')) {
            return response()->json(['Data Updated'], 200);
        } else {
            return response()->json(['message' => "OK", 'status' => '200'], 200);
        }
    }

    public function trade_car_images()
    {
        return $this->belongsToMany('Category', 'category_products');
    }
    public function list_owner(Request $req)
    {
        $now = date("Y-m-d H:i:s");
        //Check for type error
        if(!$req->type) {
            return response()->json(['error' => ' Bad request', 'status' => '400'], 400);
        }
        $query = trade_your_car::with('get_images')->with(['auction_bids' => function ($q) {
            $q->where("approved_status", "!=", 7)->orderBy('bid_price', 'DESC');
        }])->where('type', $req->type)->where('publish_status','!=','rejected');
        if($req->bids) {
            $query = $query->has('auction_bids');
        }

        $userId = auth()->user()->id;
        $query = $query->where('user_id', $userId);
        //Check for draft or published
         if($req->publish_status) {
            $query = $query->where('publish_status',$req->publish_status);
         }
         else {
          $query = $query->where('publish_status','publish');
          if($req->expired) {
             $query = $query->where('expiry_at','<=',$now);
           }
           else {
             $query = $query->where('expiry_at','>=',$now);
            }
          }

        $start = $req->start ? intval($req->start) : 0;
        $limit = $req->limit ? intval($req->limit) : config('constants.pagination.items_per_page');

        return array(
            'start' => $start,
            'limit' => $limit,
            'total' => $query->count(),
            'auctions' => $query->orderBy('id', 'DESC')->skip($start)->take($limit)->get()
        );
    }
    public function list_dealer(Request $req)
    {
        $now = date("Y-m-d H:i:s");
        $authUser = auth()->user();

        //Query current auction results
        $query = trade_your_car::with('get_images')->with(['auction_bids' => function ($q) {
            $q->where("approved_status", "!=", 7)->orderBy('bid_price', 'DESC');
        }])->where('trade_your_car.publish_status','publish')->where('trade_your_car.expiry_at', '>=', $now);

        //Published and currently has a bid by this user
        if ($req->current_bids) {
            $dealerId = $authUser->id;
            $query = $query->whereHas('auction_bids', function($query) use ($dealerId) {
                $query->where('dealer_user_id', $dealerId);
            });
        }
        //Check for Car Make
        if ($req->make) {
            $make = strtolower($req->make);
            $query->whereRaw('LOWER(trade_your_car.make) LIKE ?', ["%$make%"]);
        }
        //Check for Car Model
        if ($req->model) {
            $model = strtolower($req->model);
            $query->whereRaw('LOWER(trade_your_car.model) LIKE ?', ["%$model%"]);
        }

        if ($req->state) {
            $query->where('trade_your_car.state', $req->state);
        }

        if ($req->proximity == "1" && $authUser->zip_code) {
            $zipCodes = $this->getZipCodesByRadius($authUser->zip_code, 500);
            $zips = "[";
            foreach ($zipCodes as $key => $zipCode) {
                if ($key > 0) {
                    $zips .= ",";
                }
                $zips .= "{\"zip_code\":\"{$zipCode['zip_code']}\",\"distance\":{$zipCode['distance']}}";
            }
            $zips .= "]";
            $query->join(DB::raw("json_table('$zips', \"$[*]\" columns(zip_code varchar(10) path \"$.zip_code\", distance int path \"$.distance\")) zipcodes"), 'zipcodes.zip_code', 'trade_your_car.zip')->whereRaw('trade_your_car.radius IS NOT NULL')->whereColumn('zipcodes.distance', '<=', 'trade_your_car.radius');
        }

        $start = $req->start ? intval($req->start) : 0;
        $limit = $req->limit ? intval($req->limit) : config('constants.pagination.items_per_page');

        return array(
            'start' => $start,
            'limit' => $limit,
            'total' => $query->count(),
            'auctions' => $query->orderBy('trade_your_car.id', 'DESC')->skip($start)->take($limit)->select('trade_your_car.*')->get()
        );
    }

    public function list_dealer_top(Request $req)
    {
        $dealerId = auth()->user()->id;
        $query = trade_your_car::with('get_images')
            ->with(['auction_bids' => function ($q) {
                $q->orderBy('bid_price', 'DESC');
            }])
            ->where('trade_your_car.publish_status', 'publish')
            ->whereHas('auction_bid_with_max_price', function($q) use ($dealerId) {
                $q->where('dealer_user_id', $dealerId);
            })
            ->orderBy('trade_your_car.id', 'DESC');

        $start = $req->start ? intval($req->start) : 0;
        $limit = $req->limit ? intval($req->limit) : config('constants.pagination.items_per_page');

        return array(
            'start' => $start,
            'limit' => $limit,
            'total' => $query->count(),
            'auctions' => $query->skip($start)->take($limit)->get()
        );
    }

    public function listSell(Request $req)
    {
        $query = trade_your_car::with('get_images')->where('type', 'sell');
        //Car owner
        $user = auth()->user();
        if($user->user_type == 'Car Owner') {
            $query = $query->where('user_id', $user->id);
            //Check for draft or published
            if($req->publish_status) {
                $query = $query->where('publish_status',$req->publish_status);
            }
            else {
                $query = $query->where('publish_status','publish');
            }

        }
        return $query->orderBy('id', 'DESC')->get();


    }

    public function GetAll()
    {

        return trade_your_car::with('get_images')->where('publish_status', 'publish')->where('created_at', '>=', Carbon::now()->subDay())->get();
    }


    public function section($section = null, $filter = null)
    {

        if($section == 'applied'){
            $bid_status = 0;
        }elseif($section == 'won'){
            $bid_status = 2;
        }elseif($section == 'lost'){
            $bid_status = 7;
        }


        if($filter == ''){
            return auction_bids::with('get_images')->where('auction_bids.approved_status', '=', $bid_status)->join('trade_your_car', 'trade_your_car.id', '=', 'auction_bids.auction_item_id')->get();
        }else{
            return auction_bids::with('get_images')->where('auction_bids.approved_status', '=', $bid_status)->where('type', '=', $filter)->join('trade_your_car', 'trade_your_car.id', '=', 'auction_bids.auction_item_id')->get();
        }
    }

    private function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

    public function listAll(Request $req)
    {
        $make = $req->input('make');
        $model = $req->input('model');

        $location = $req->input('location');
        $user_city = $this->ip_info("Visitor", "City");

        if($req->input('make') && $req->input('model')){
            $filter_query = trade_your_car::with('get_images')->where('make', $make)->where('model', $model)->where('publish_status', 'publish')->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->get();
        }elseif($req->input('model')){
            $filter_query = trade_your_car::with('get_images')->where('model', $model)->where('publish_status', 'publish')->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->get();
        }elseif($req->input('make')){
            $filter_query = trade_your_car::with('get_images')->where('make', $make)->where('publish_status', 'publish')->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->get();
        }else{
            $filter_query = trade_your_car::with('get_images')->where('publish_status', 'publish')->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->get();
        }

      	if($location == 'true') {
    	    return $filter_query->where('city', $user_city);
		}else{
            return $filter_query;
        }



    }



    public function bidSell($user_id = null)
    {

        return auction_bids::with('get_images')
            ->join('trade_your_car', 'auction_bids.auction_item_id', '=', 'trade_your_car.id')
            ->join('users', 'auction_bids.dealer_user_id', '=', 'users.id')
            ->select('users.*', 'trade_your_car.*', 'auction_bids.*')
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 'sell')
            ->where('auction_bids.approved_status', '=', '1')
            ->get();
    }

    public function bidTrade($user_id = null)
    {

            return auction_bids::with('get_images')
            ->join('trade_your_car', 'trade_your_car.id', '=', 'auction_bids.auction_item_id')
            ->join('users', 'users.id', '=', 'trade_your_car.user_id')
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 'trade')
            ->where('auction_bids.approved_status', '=', '1')
            ->get();

            //return trade_your_car::with('auction_bids')->where('user_id', '=', $user_id)->where('type', '=', 'trade')->get();
    }


    function addBid(Request $req) {
        $auctionItemId = $req->input('item_id');
        $dealer = auth()->user();
        $dealerId = $dealer->id;
        $query = auction_bids::where('dealer_user_id', '=', $dealerId)->where('auction_item_id', '=', $auctionItemId);

        if ($query->count() === 2) {
            return response()->json(['message' => "You already have 2 bids on this item", 'status' => '400'], 400);
        }

        // Get top bid
        $topBid = auction_bids::where('auction_item_id', '=', $auctionItemId)
            ->orderBy('bid_price', 'DESC')
            ->first();
        $totalCount = auction_bids::where('auction_item_id', '=', $auctionItemId)->count();

        // Do not allow dealer to bid when they are the top bid
        if ($topBid !== null && $totalCount > 1 && $topBid->dealer_user_id == $dealerId) {
            return response()->json(['message' => "You already have top bid on this item", 'status' => '400'], 400);
        }

        $bids = new auction_bids;
        $bids->bid_price=$req->input('bid_amount');
        $bids->dealer_user_id=$dealerId;
        $bids->auction_item_id=$req->input('item_id');
        $bids->owner_user=$req->input('owner_id');
        $bids->save();

        // Send email notification
        $owner = User::find($bids->owner_user);
        $auctionItem = trade_your_car::find($bids->auction_item_id);
        $itemName = "{$auctionItem->make} {$auctionItem->model}, {$auctionItem->vin}";
        Mail::to($owner)->send(new Bid(
            $owner->name,
            $dealer,
            $itemName,
            $bids->bid_price
        ));

        return response()->json(['message' => "OK", 'status' => '200'], 200);
    }

    function messaging_conversation() {
        $user = auth()->user();
        if ($user->user_type == 'Car Owner') {
            return messaging::join('users', 'users.id', '=', 'messaging.dealer_id')
                ->where('owner_id', '=', $user->id)
                ->where('approved_status', '=', 1)
                ->groupBy('item_id')
                ->orderBy('messaging.id', 'desc')
                ->select('messaging.*', 'users.dp', 'users.name')
                ->get();
        } elseif ($user->user_type == 'Car Dealer') {
            return messaging::join('users', 'users.id', '=', 'messaging.owner_id')
                ->where('dealer_id', '=', $user->id)
                ->where('approved_status', '=', 1)
                ->groupBy('item_id')
                ->orderBy('messaging.id', 'DESC')
                ->select('messaging.*', 'users.dp', 'users.name')
                ->get();
        }
    }

    function conversation($conversation = null){

        return DB::table('messaging')
            ->join('users', 'users.id', '=', 'messaging.sent_by')
            ->select('messaging.*', 'users.dp', 'users.name')
            ->where('item_id', '=', $conversation)
            ->get();

    }


    function addMessaging(Request $req){
        $user = auth()->user();

        if ($user->user_type == 'Car Owner' && !$req->input('dealer_id')) {
            return response()->json(['message' => "Dealer ID is required", 'status' => '400'], 400);
        }

        if ($user->user_type == 'Car Dealer' && !$req->input('owner_id')) {
            return response()->json(['message' => "Owner ID is required", 'status' => '400'], 400);
        }

        $recipient = null;
        $msg = new messaging;

        if ($user->user_type == 'Car Owner') {
            $msg->owner_id = $user->id;
            $msg->dealer_id = $req->input('dealer_id');
            $recipient = User::find($msg->dealer_id);
        } else if ($user->user_type == 'Car Dealer') {
            $msg->owner_id = $req->input('owner_id');
            $msg->dealer_id = $user->id;
            $recipient = User::find($msg->owner_id);
        }

        $msg->sent_by=$user->id;
        $msg->item_id=$req->input('item_id');
        $msg->message=$req->input('message');
        $msg->approved_status = 1;
        $msg->save();

        // Send email notification
        if ($recipient) {
            $item = trade_your_car::find($msg->item_id);
            if ($item) {
                Mail::to($recipient)->send(new Message(
                    $user->name,
                    $recipient->name,
                    "{$item->make} {$item->model}, {$item->vin}"
                ));
            }
        }

        return response()->json(['message' => "OK", 'status' => '200'], 200);

    }


    function BidStatus(Request $req){

        $msg   = new messaging;

        $bid_id =$req->input('bid_id');
        $status =$req->input('status');
        if($status == 'Accepted'){

            $userdp = auction_bids::where('bid_id', $bid_id)->update(['approved_status' => DB::raw('approved_status + 1')]);
            return response()->json(['message' => "OK", 'status' => '200'], 200);

        }else{
            $userdp = auction_bids::where('bid_id', $bid_id)->update(array('approved_status' => 7));
            return response()->json(['message' => "OK", 'status' => '200'], 200);
        }

    }

    public function bidHistory($auction_id = null)
    {
        return auction_bids::where('auction_item_id',$auction_id)
            ->join('trade_your_car', 'auction_bids.auction_item_id', '=', 'trade_your_car.id')
            ->join('users', 'auction_bids.dealer_user_id', '=', 'users.id')
            ->select('trade_your_car.created_at as start_date', 'auction_bids.bid_price')
            ->get();
    }

    public function get_active_states(Request $req)
    {
        $now = date("Y-m-d H:i:s");
        $query = trade_your_car::where('publish_status','!=','rejected')->where('expiry_at','>=',$now)->select('state')->distinct();
        return $query->get();
    }

    public function getZipCodesByRadius($zip_code, $radius)
    {
        $key = "$zip_code/$radius/mile";
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $url = config('constants.zipcode_api.base_url') . $key;
        $res = Http::get($url);
        $zipCodes = $res->json('zip_codes');
        Cache::forever($key, $zipCodes);
        return $zipCodes;
    }


}
