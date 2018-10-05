<?php

namespace App\Http\Controllers\API_V2;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Input;
use Hash;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Account;
use Mail;
use Illuminate\Routing\Controller as BaseController;

class Api2Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['LoginAttendee']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function APILogin(Request $request)
    {
        $email =        $request->get('email');
        $password =     $request->get('password');
        $device_type =  $request->get('device_type');  // Web, IOS , Android
        $device_key =   $request->get('device_key'); // for push notification
        $login_type =   $request->get('login_type'); // manual,facebook,google
        $social_id =    $request->get('social_id'); // facebook,google  emailID or there email used
        $credentials =  $request->only('email', 'password');
        $rules = [
            'login_type' => 'required|in:manual,facebook,google',
            'device_type' => 'required|in:web,android,ios',
        ];
        $messages = [
            'login_type.required' => 'login_type is required',
            'login_type.in' => 'invalid login type value',
            'device_type.required' => 'device_type is required',
            'device_type.in' => 'invalid login type value',

        ];
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validation->messages()->toArray(),
            ]);
        }

        
        if($login_type=='manual'){
            //Validation
            $rules = [
                'email'        => [
                    'required',
                    'email'
                ],
                'password'  => 'required',

            ];
            $messages = [
                'email.email'         => 'Please enter a valid E-mail address.',
                'email.required'      => 'E-mail address is required.',
                'password.required'            => 'password is required',
            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validation->messages()->toArray(),
                ]);
            }


            $user = User::where('email', $email)
            //->where('is_active', 1)
            //->where('is_guest', 0)
            //->where('is_admin', 0)
            ->first();
            //var_dump($user);die();
            //var_dump( $user);

            //$user = User::first();
            if(!$user){
                 return response()->json([
                    'status'  => 'false',
                    'error' => 'Email id not exist',
                ]);
            }


            $validCredentials = Hash::check( $password , $user->getAuthPassword());
            if ($validCredentials) {
                $token = JWTAuth::fromUser($user);
                $user->remember_token= $token;
                $user->save();
                return response()->json([
                    'status'  => 'true',
                    'token'   => $token,
                ]);
            }else{
                return response()->json([
                    'status'  => 'false',
                    'error' => 'invalid username or password',
                ]);
            }
        }else if($login_type=='facebook'){ //login via facebook
            
            $rules = [
                'social_id' => 'required', // Send email from facebook api frontend
            ];
            $messages = [
                'social_id.required' => 'facebook id is required',
            ];
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validation->messages()->toArray(),
                ]);
            }    

            $user = User::where('email', $social_id)->first();
            if($user){
                $token = JWTAuth::fromUser($user);
                $user->remember_token= $token;
                $user->save();
                return response()->json([
                    'status'  => 'true',
                    'token'   => $token,
                ]);
            }else{
                return response()->json([
                    'status'  => 'false',
                    'error' => 'Facebook id not exist',
                ]);
            }

        }else if($login_type=='google'){  //login via google

            $rules = [
                'social_id' => 'required', // Send email from facebook api frontend
            ];
            $messages = [
                'social_id.required' => 'Google id required',
            ];
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validation->messages()->toArray(),
                ]);
            }        
            $user = User::where('email', $social_id)->first();
            if ($user) {
                $token = JWTAuth::fromUser($user);
                $user->remember_token= $token;
                $user->save();
                return response()->json([
                    'status'  => 'true',
                    'token'   => $token,
                ]);
            }else{
                return response()->json([
                    'status'  => 'false',
                    'error' => 'Google id not exist',
                ]);
            }

        }
    }

    
    // API sign-up
    public function APISignup(Request $request)
    {
        $rules = [
                'email'      => ['required','email','unique:users,email'],
                'phone'      => 'required',
                'password'   => ['required','min:6'],
                'username'   => ['required','unique:users,username'],
            ];
        
        $messages = [
            'email.required' => 'the email address is required',
            'email.email' => 'invalid email',
            'email.unique' => 'email aready used',
            'phone.required' => 'the phone number is required',
            'username.required' => 'username is required',

        ];
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validation->messages()->toArray(),
            ]);
        }

        $email = $request->get('email');
        $phone = $request->get('phone');
        $password = $request->get('password');  
        $username = $request->get('username'); 
        $gallery = $request->get('gallery'); 
        $device_key = $request->get('device_key'); 



        $account = new Account();
        $account->last_name = '';
        $account->first_name = '';
        $account->email = $email;
        $account->currency_id = 2;
        $account->timezone_id = 30;
        $account->account_type = 'exhibitor';
        $account->save();
        
        /*
        Save  attendee  account
        */

        /*
         *
        Create Attendee user account
        */
        $user = new User();
        $user->account_id = $account->id;
        $user->username = $username;
        $user->first_name = '';
        $user->last_name = '';
        $user->email = $email;
        $user->password = Hash::make($password);;
        $user->phone = $phone;
        $user->is_registered = 1;
        $user->is_parent = 1;
        $user->api_token = '';
        $user->save();

        //$user = User::where('email', $email)->first();
        if ($user) {
            $token = JWTAuth::fromUser($user);
            
             // TODO: Do this async?
            Mail::send('Emails.ConfirmEmail',
            ['first_name' => $user->first_name, 'confirmation_code' => $user->confirmation_code],
            function ($message) use ($request) {
                $message->to($request->get('email'), $request->get('first_name'))
                    ->subject('Thank you for registering for Attendize');
            });

            return response()->json([
                'status'  => 'true',
                'message'   => 'Successfully registered, check your email for account confirmation',
            ]);
        }else{
            return response()->json([
                'status'  => 'false',
                'error' => 'error someting went wrong',
            ]);
        }

       

        
    }


    // API Change Password
    public function APIChangepassword(Request $request)
    {
       $rules = [
                'token'                 => 'required',
                'current-password'      => 'required',
                'password'              => 'required|same:password_confirmation',
                'password_confirmation' => 'required|same:password',     
            ];
        
        $messages = [
            'current-password.required' => 'Please enter current password',
            'password.required' => 'Please enter password',

        ];
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validation->messages()->toArray(),
            ]);
        }


        $email = $request->get('token');
        $phone = $request->get('phone');
        $password = $request->get('password');  
        $username = $request->get('username'); 
        $gallery = $request->get('gallery'); 
        $current_password = $request->get('current-password'); 
        $password_confirmation = $request->get('password_confirmation');
        $user = User::find( JWTAuth::toUser($request->input('token'))->id )->first();
        $cur_password =$user->password;
            
        if(!Hash::check($current_password, $cur_password)){
            return response()->json([
                'status'   => 'error',
                'messages' => 'invalid current password'
            ]);
        }else{
            $user->password=$password_confirmation;
            $user->save();
            return response()->json([
                    'status'   => 'success',
                    'messages' => 'Password successfully updated'
            ]);
        }
        
    }
}