<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator, DateTime, DB, Hash, Request, File, Config, Helpers;
use Tymon\JWTAuth\Contracts\JWTSubject;
use JWTAuth;
use JWTAuthException;
use File;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
     /**
     * Get the identifier that will be stored in the subject claim of the JWT
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    // signUpRules
    public static $signupRules = array(
        'fullname' => 'required',
        'email' => 'required|email|unique:users',
        'phone' => 'required|numeric|unique:users',
        'password' => 'required|min:6',
         );
    protected function register($request)
    {
        $validation = Validator::make($request, self::$signupRules);
        if($validation->passes()){
            if(user::where('email',$request['email'])->first())
            {
                return response()->json(['status'=>true,'message'=>'Email ID already exit','response'=>''],401);
            }
            if(user::where('phone',$request['phone'])->first())
            {
                return response()->json(['status'=>true,'message'=>'Phone number already exit','response'=>''],401);
            }
            $location = self::getlatlong((isset($request['address'])?$request['address']:'').' '.(isset($request['city'])?$request['city']:'').' '.(isset($request['country'])?$request['country']:''));
            $new_user = new User;
            $new_user->user_role    = (isset($request['user_role'])?$request['user_role']:'');
            $new_user->fullname     = (isset($request['fullname'])?$request['fullname']:'');
            $new_user->username     = (isset($request['username'])?$request['username']:'');
            $new_user->email        = (isset($request['email'])?$request['email']:'');
            $new_user->phone        = (isset($request['phone'])?$request['phone']:'');
            $new_user->password     = (isset($request['password'])?bcrypt($request['password']):'');
            $new_user->country      = (isset($request['country'])?$request['country']:'');
            $new_user->city         = (isset($request['city'])?$request['city']:'');
            $new_user->address      = (isset($request['address'])?$request['address']:'');
            $new_user->lattitude    = (isset($location['latitude'])?$location['latitude']:'');
            $new_user->longitude    = (isset($location['longitude'])?$location['longitude']:'');
            $new_user->camera       = (isset($location['camera'])?1:0);
            if($request['register_type']=='social')
            {
                $new_user->image    = (isset($location['image'])?self::convert_social_image_url($request['image']):'');
            }else
            {
                $new_user->image    = (isset($location['image'])?self::uploaded_file($request):'');
            }
            $new_user->created_at   = new DateTime;
            $new_user->updated_at   = new DateTime;
            $new_user->save();
            if (!$new_user) {
               return response()->json(['status'=>false,'message'=>'Something went wrong','response'=>''],400);
            }
            return response()->json(['status'=>true,'message'=>'User created successfully','response'=>$new_user],201);
        } else {
            return response()->json(['status' => false, 'message' => $validation->getMessageBag()->first(),'response'=>''], 400);
        }
    }
    protected function getlatlong($address)
    {
        if(empty(trim($address))) return;
        $return = array();
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $return['latitude'] = $output->results[0]->geometry->location->lat;
        $return['longitude'] = $output->results[0]->geometry->location->lng;
        return $return;
    }
    // loginRules
    public static $loginRules = array(
        'phone' => 'required|numeric',
        'password' => 'required',
    );
    protected function login($request)
    {
        $validation = Validator::make($request, self::$loginRules);
        if($validation->passes()){

            $credentials = $request;
            $token = null;
            try {
               if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => false, 'response' => 'Invalid phone and password','response'=>''], 422);
               }
            } catch (JWTAuthException $e) {
                return response()->json(['status' => false, 'message' => 'failed to create token, please try after some time','response'=>''], 500);
            }
        } else {
            return response()->json(['status' => false, 'message'=>$validation->getMessageBag()->first(), 'response' => '' ], 400);
        }
        $token = compact('token');
        $update_token['token'] = $token['token'];
        user::where('phone',$request['phone'])->update($update_token);
        return response()->json(['status' => true,'message'=>'token created','response'=>['token'=>$token['token']]] , 200);
    }
    protected function profile($request)
    {
        return user::where('token',$request->header('token'))->first();
    }
    protected function logout($request)
    {
        $expire_token = array();
        $expire_token['token'] = '';
        user::where('token',$request->header('token'))->update($expire_token);
        return response()->json(['status' => true,'message'=>'logout success','response'=>''] , 200);
    }
    protected function deleteProfile($request)
    {
        user::where('token',$request->header('token'))->delete();
        return response()->json(['status' => true,'message'=>'delete success','response'=>''] , 200);
    }
    public static $confirmphone_rule = array(
        'phone' => 'required|numeric',
    );
    protected function confirm_phone($request)
    {
        $validation = Validator::make($request, self::$confirmphone_rule);
        if($validation->passes()){
            if(empty(user::where('phone',$request['phone'])->first())){
                return response()->json(['status' => false, 'message'=>'Phone number is not exit', 'response' => '' ], 200);
            }else{
                return response()->json(['status' => false, 'message'=>'Phone number is exit', 'response' => '' ], 401);
            }
        }else{
            return response()->json(['status' => false, 'message'=>$validation->getMessageBag()->first(), 'response' => '' ], 400);
        }
    }
    public function updateprofile($request)
    {
        $location = self::getlatlong((isset($request['address'])?$request['address']:'').' '.(isset($request['city'])?$request['city']:'').' '.(isset($request['country'])?$request['country']:''));
        $update_user = array();
        $update_user['fullname']     = (isset($request['fullname'])?$request['fullname']:'');
        $update_user['username']     = (isset($request['username'])?$request['username']:'');
        $update_user['email']        = (isset($request['email'])?$request['email']:'');
        $update_user['phone']        = (isset($request['phone'])?$request['phone']:'');
        $update_user['country']      = (isset($request['country'])?$request['country']:'');
        $update_user['city']         = (isset($request['city'])?$request['city']:'');
        $update_user['address']      = (isset($request['address'])?$request['address']:'');
        if(!empty($location))
        {
            $update_user['lattitude']    = (isset($location['latitude'])?$location['latitude']:'');
            $update_user['longitude']    = (isset($location['longitude'])?$location['longitude']:'');
        }
        $update_user['camera']       = (isset($location['camera'])?1:0);
        if($location['image'])
        {
            $update_user['image']        = self::fileupload($request['image']);
        }
        $update_user['updated_at']   = new DateTime;
        user::where('token',$request->header('token'))->update($update_user);
        return response()->json(['status'=>true,'message'=>'User profile updated','response'=>user::where('token',$request->header('token'))->first()],200);
    }
    public function convert_social_image_url($newrequest)
    {
        $fileContents = file_get_contents($newrequest['login_image']);
        File::put(public_path() . '/images/uploads/' . $newrequest['login_social_id'] . ".jpg", $fileContents);
        return 'clicpic/public/images/uploads/'.$newrequest['login_social_id'] .'.jpg';
    }
    public function fileupload($request){
        $file = $request->file('image');
        $destinationPath = 'public/images/uploads';
        $filename = time().'_'.$file->getClientOriginalName();
        $upload_success = $file->move($destinationPath, $filename);
        $uploaded_file = 'clicpic/public/images/uploads/'.$filename;            
        return $uploaded_file;
   }
}
