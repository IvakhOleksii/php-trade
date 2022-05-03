<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\trade_your_car;

use App\Models\sell_car_images;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Validator;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Http;
use DB;

class Trade_Your_CarController extends Controller
{
    //
    function add(Request $req){
        //return $req->input();
        $car   = new trade_your_car;
        $car->vin=$req->input('vin');
        $car->odometer=$req->input('odometer');
        $car->transmission=$req->input('transmission');
        $car->trim=$req->input('trim');
        $car->drivetrain=$req->input('drivetrain');
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
        $car->status="Pending";
        $car->save();

        $sell_id = $car->id;

        $allowedfileExtension=['jpg','png','JPG','PNG','jpeg','JPEG'];
        /// starting front_Seats Image Upload
        if($req->hasFile('front_Seats')) {
            // return response()->json(['message' => "OK", 'status' => '200'], 200);

            $files = $req->file('front_Seats');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($req->front_Seats as $mediaFiles) {

                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();
                        $file_name = str_replace("public/images/","",$url);
                        //store image file into directory and db
                        $save_front_Seats= new sell_car_images();
                        $save_front_Seats->image_key = $name;
                        $save_front_Seats->url = $file_name;
                        $save_front_Seats->sell_car_id = $sell_id;
                        $save_front_Seats->is_primary = 2;
                        $save_front_Seats->save();
                    }
                } else {
                    //return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending front_Seats Image Upload

        /// starting dash Image Upload
        if($req->hasFile('dash')) {
            // return response()->json(['message' => "OK", 'status' => '200'], 200);

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
                        $save_dash = new sell_car_images();
                        $save_dash->image_key = $name;
                        $save_dash->url = $file_name;
                        $save_dash->sell_car_id = $sell_id;
                        $save_dash->is_primary = 3;
                        $save_dash->save();
                    }
                } else {
                    //return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending dash Image Upload


        /// starting navigation Image Upload
        if($req->hasFile('navigation')) {
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
                        $save_navigation = new sell_car_images();
                        $save_navigation->image_key = $name;
                        $save_navigation->url = $file_name;
                        $save_navigation->sell_car_id = $sell_id;
                        $save_navigation->is_primary = 4;
                        $save_navigation->save();
                    }
                } else {
                    //return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending dash Image Upload


        /// starting front Image Upload
        if($req->hasFile('front')) {
            // return response()->json(['message' => "OK", 'status' => '200'], 200);

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
                        $save_front = new sell_car_images();
                        $save_front->image_key = $name;
                        $save_front->url = $file_name;
                        $save_front->sell_car_id = $sell_id;
                        $save_front->is_primary = 5;
                        $save_front->save();
                    }
                } else {
                    //return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending front Image Upload


        /// starting rear Image Upload
        if($req->hasFile('rear')) {
            // return response()->json(['message' => "OK", 'status' => '200'], 200);

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
                        $save_rear = new sell_car_images();
                        $save_rear->image_key = $name;
                        $save_rear->url = $file_name;
                        $save_rear->sell_car_id = $sell_id;
                        $save_rear->is_primary = 6;
                        $save_rear->save();
                    }
                } else {
                    //return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending rear Image Upload





        /// starting driver_s_side Image Upload
        if($req->hasFile('driver_s_side')) {
            // return response()->json(['message' => "OK", 'status' => '200'], 200);

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
                        $save_driver_s_side = new sell_car_images();
                        $save_driver_s_side->image_key = $name;
                        $save_driver_s_side->url = $file_name;
                        $save_driver_s_side->sell_car_id = $sell_id;
                        $save_driver_s_side->is_primary = 7;
                        $save_driver_s_side->save();
                    }
                } else {
                    //return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending driver_s_side Image Upload



        /// starting driver_s_side Image Upload
        if($req->hasFile('passenger_s_side')) {
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
                        $save_passenger_s_side = new sell_car_images();
                        $save_passenger_s_side->image_key = $name;
                        $save_passenger_s_side->url = $file_name;
                        $save_passenger_s_side->sell_car_id = $sell_id;
                        $save_passenger_s_side->is_primary = 8;
                        $save_passenger_s_side->save();
                    }
                } else {
                    //return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending passenger_s_side Image Upload

        /// starting Image Upload
        if($req->hasFile('additional_photos')) {
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
                        $save = new sell_car_images();
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

        return response()->json(['file_uploaded'], 200);
    }

    public function sell_car_images()
    {
        return $this->belongsToMany('Category', 'category_products');
    }
    public function list()
    {
        return trade_your_car::with('get_images')->get();
    }



}
