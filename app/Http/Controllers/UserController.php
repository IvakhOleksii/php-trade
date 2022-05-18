<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Models\contact_us;

use App\Mail\Registration;
use App\Mail\ApprovalRequest;

use Illuminate\Support\Facades\Hash;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends Controller
{
    //
    function register(Request $req){
        $now = date("Y-m-d H:i:s");

        // $result = $req->file('license')->store('images');
        // return ["result"=>$result]; exit;
        //return $req->input(); exit;
        $user   = new User;
        $user->name=$req->input('name');
        $user->email=$req->input('email');
        $user->user_type=$req->input('user_type');
        $user->password=Hash::make($req->input('password'));
        $user->state=$req->input('state');
        $user->city=$req->input('city');
        $user->address=$req->input('address');
        $user->phone=$req->input('phone');
        $user->dealerName=$req->input('dealername');
        $user->companywebsite=$req->input('companywebsite');
        $user->car_make=$req->input('car_make');
        $user->latitude=$req->input('latitude');
        $user->longitude=$req->input('longitude');
        $user->zip_code=$req->input('zip_code');
        $user->approved_status = 0;
        $user->created_at = $now;
        $user->updated_at = $now;

        //Add the default image for users if not there
        if($user->user_type == 'Car Owner' && !$req->hasFile('dealer_image')) {
            $file_name = "/default-user.jpeg";
            $user->dp = $file_name;
        }

        $userss = User::where('email', '=', $req->input('email'))->first();
        if ($userss === null) {
            $allowedfileExtension=['jpg','png','PNG','JPG','jpeg','JPEG'];


            /// starting dp Upload
            $name = '';
            $url = '';
            if($req->hasFile('dealer_image')) {
                // return response()->json(['message' => "OK", 'status' => '200'], 200);
                $files = $req->file('dealer_image');

                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();

                    $check = in_array($extension,$allowedfileExtension);

                    if($check) {
                        foreach($req->dealer_image as $mediaFiles) {

                            $url = $mediaFiles->store('public/images/');
                            $name = $mediaFiles->getClientOriginalName();

                            //store image file into directory and db
                            $file_name = str_replace("public/images/","",$url);
                            $user->dp = $file_name;
                        }
                    } else {
                        return response()->json(['invalid_file_format'], 422);
                    }

                    //return response()->json(['file_uploaded'], 200);
                }

            }
            /// ending dp Upload


            /// starting Image Upload
            $name = '';
            $url = '';
            if($req->hasFile('license')) {
                // return response()->json(['message' => "OK", 'status' => '200'], 200);

                $files = $req->file('license');

                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();

                    $check = in_array($extension,$allowedfileExtension);

                    if($check) {
                        foreach($req->license as $mediaFiles) {

                            $url = $mediaFiles->store('public/images/');
                            $name = $mediaFiles->getClientOriginalName();

                            //store image file into directory and db
                            $file_name = str_replace("public/images/","",$url);
                            //$user->license = $name;
                            $user->dealer_licence = $file_name;
                        }
                    } else {
                        return response()->json(['invalid_file_format'], 422);
                    }

                    //return response()->json(['file_uploaded'], 200);

                }

            }
            /// ending Image Upload

            
            $user->save();
            
            // Generate jwt key that expires in a week for owner user
            $verifyKey = "";
            if ($user->user_type == "Car Owner") {
                $issuedAt = time();
                $expirationTime = $issuedAt + 7 * 60 * 24 * 60;
                $payload = array(
                    'id' => $user->id,
                    'iat' => $issuedAt,
                    'exp' => $expirationTime,
                );
                $verifyKey = JWT::encode($payload, env('VERIFY_JWT_SECRET', ''), 'HS256');
                $user->verify_key = $verifyKey;
                $user->save();
            }

            // Send email notification
            Mail::to($user)->send(new Registration($user->name, $user->user_type == "Car Owner" ? "owner" : "dealer", $verifyKey));

            $response = [
                'message' => "OK",
                'status' => '200'
            ];

            if ($user->user_type == 'Car Dealer') {
                // Send an email to reviewer
                Mail::to([
                    'email' => env('REVIEWER_EMAIL', '')
                ])->send(new ApprovalRequest($user));
            }

            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Exist', 'status' => '300'], 300);
        }
    }

    function contact(Request $req){
        //return $req->input();
        $contact   = new contact_us();
        $contact->first_name=$req->input('first_name');
        $contact->last_name=$req->input('last_name');
        $contact->company=$req->input('company');
        $contact->email=$req->input('email');
        $contact->phone_number=$req->input('phone_number');
        $contact->message=$req->input('message');
        $contact->save();
        return response()->json(['message' => "OK", 'status' => '200'], 200);
    }

    function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user->approved_status != 1) {
            return response()->json([
                'error' => $user->user_type == 'Car Dealer' ? 'NotApproved' : 'NotVerified',
                'status' => 400
            ], 400);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'WrongCredentials', 'status' => '320'], 320);
        }

        $user_data = array("id"=>$user->id, "name"=>$user->name, "email"=>$user->email, "user_type"=>$user->user_type, "state"=>$user->state, "city"=>$user->city, "address"=>$user->address, "phone"=>$user->phone, "dealerName"=>$user->dealerName, "companywebsite"=>$user->companywebsite, "car_make"=>$user->car_make, "zip_code"=>$user->zip_code, "Licence"=>$user->dealer_licence, "dealer_image"=>$user->dp);

        return response()->json([
            'message' => "OK",
            'data' => $user_data,
            'token' => $token,
            'status' => '200'
        ], 200);
    }

    function forgetPass(Request $req)
    {
        $email = $req->input('email');

        $userss = User::where('email', '=', $req->input('email'))->first();
        if ($userss == null) {
            return response()->json(['Error' => "Email Not Exist", 'status' => '320'], 320);
        }else{
            return response()->json(['Message' => "Email Sent", 'status' => '200'], 200);
        }

    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $fname = $request->input('name');
        $email = $request->input('email');
        $user_type = $request->input('user_type');
        $state = $request->input('state');
        $city = $request->input('city');
        $address = $request->input('address');
        $phone = $request->input('phone');
        $dealername = $request->input('dealername');
        $companywebsite = $request->input('companywebsite');
        $car_make = $request->input('car_make');
        $zip_code = $request->input('zip_code');

         $url = '';
         $allowedfileExtension=['jpg','png','PNG','JPG','jpeg','JPEG'];

         if($request->hasFile('dealer_licence')) {
            // return response()->json(['message' => "OK", 'status' => '200'], 200);


            $files = $request->file('dealer_licence');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($request->dealer_licence as $mediaFiles) {

                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();

                        //store image file into directory and db
                        $file_name = str_replace("public/images/","",$url);
                        $userdp = $user->update(array('dealer_licence' => $file_name));
                    }
                } else {
                    return response()->json(['message' => 'invalid_file_format', 'status' => '422'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending licence Upload






        if($request->hasFile('dealer_image')) {
        // return response()->json(['message' => "OK", 'status' => '200'], 200);


            $files = $request->file('dealer_image');

            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $check = in_array($extension,$allowedfileExtension);

                if($check) {
                    foreach($request->dealer_image as $mediaFiles) {

                        $url = $mediaFiles->store('public/images/');
                        $name = $mediaFiles->getClientOriginalName();

                        //store image file into directory and db
                        $file_name = str_replace("public/images/","",$url);
                        $userdp = $user->update(array('dp' => $file_name));
                    }
                } else {
                    return response()->json(['invalid_file_format'], 422);
                }

                //return response()->json(['file_uploaded'], 200);

            }

        }
        /// ending dp Upload

        $user->update(array(
            'car_make' => $car_make,
            'name' => $fname,
            'email' => $email,
            'user_type' => $user_type,
            'state' => $state,
            'city' => $city,
            'address' => $address,
            'phone' => $phone,
            'dealername' => $dealername,
            'companywebsite' => $companywebsite,
            'zip_code' => $zip_code
        ));

        $user_data = array(
            "id"=>$user->id,
            "name"=>$user->name,
            "email"=>$user->email,
            "user_type"=>$user->user_type,
            "state"=>$user->state,
            "city"=>$user->city,
            "address"=>$user->address,
            "phone"=>$user->phone,
            "dealername"=>$user->dealername,
            "companywebsite"=>$user->companywebsite,
            "car_make"=>$user->car_make,
            "Licence"=>$user->dealer_licence,
            "dealer_image"=>$user->dp,
            "zip_code"=>$user->zip_code
        );

        return response()->json([
            'message' => "OK",
            'data' => $user_data,
            'status' => '200'
        ], 200);
    }

    public function checkVin(Request $request) {

        $res = Http::get("https://api.carsxe.com/specs?key=ee7aysmdg_svxt5xwh5_lxwq37r8m&vin=$request->vin&format=json");
        return json_encode(['message' => 'success', 'data' => $res->json()]);
    }

    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'newpassword' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages(), 'status' => '400'], 400);
        }

        $user = auth()->user();

        if (!Hash::check($request->password, $user->password))
        {
            return response()->json(['error' => 'Wrong Old Password', 'status' => '320'], 320);
        } else {
            $updated_password = Hash::make($request->newpassword);
            $user->update(['password' => $updated_password]);
            return response()->json(['message' => 'Password Updated', 'status' => '200'], 200);
        }
    }

    public function logout(Request $request) {
        if (auth()->user()) {
            auth()->logout();
        }
        return response()->json(['message' => 'User Logged out', 'status' => '200'], 200);
    }

    public function verifyUser($key) {
        try {
            $decoded = JWT::decode($key, new Key(env('VERIFY_JWT_SECRET', ''), 'HS256'));
            $user = User::find($decoded->id);
            if (!$user) {
                echo "User does not exist.";
            } else if ($user->approved_status == 1) {
                echo "User is already verified.";
            } else if ($user->verify_key != $key) {
                echo "Your verification key is not valid.";
            } else {
                $user->approved_status = 1;
                $user->verify_key = null;
                $user->save();

                echo "Thank you for verifying your account! Redirecting to " . env('FRONT_URL', '') . "...<script>setTimeout(function() { window.location.href = '" . env('FRONT_URL', '') . "'; }, 3000);</script>";
            }
        } catch (\Exception $e) {
            echo "Your verification link is not valid or has expired.";
        }
    }

}
