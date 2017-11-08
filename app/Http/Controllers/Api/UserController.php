<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/register",
     *     summary="Register User",
     *     tags={"UserController"},
     *     description="register user here uniqe is email and phone",
     *     operationId="register",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="fullname",
     *         in="formData",
     *         description="Name of register user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="user_role",
     *         in="formData",
     *         description="User role eg(photographer, user)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="Email of register user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="phone",
     *         in="formData",
     *         description="Phone of register user",
     *         required=true,
     *         type="number"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password of register user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="country",
     *         in="formData",
     *         description="Country of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="city",
     *         in="formData",
     *         description="City of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="address",
     *         in="formData",
     *         description="Address of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="camera",
     *         in="formData",
     *         description="Camera if yes 1 else 0",
     *         required=false,
     *         type="number"
     *     ),
     *     @SWG\Parameter(
     *         name="register_type",
     *         in="formData",
     *         description="Register user from social or app",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="image",
     *         in="formData",
     *         description="Image of register user",
     *         required=true,
     *         type="file"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Request created successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     ),  
     *     @SWG\Response(
     *         response="401",
     *         description="NOt authorize to complete this request",
     *     )     
     * )
     */
    public function apiregister(Request $request)
    {
        return user::register($request->all());
    }
    /**
     * @SWG\Post(
     *     path="/login",
     *     summary="Login User",
     *     tags={"UserController"},
     *     description="Login user here uniqe is phone and password",
     *     operationId="login",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="phone",
     *         in="formData",
     *         description="Phone of register user",
     *         required=true,
     *         type="number"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password of register user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     ), 
     *     @SWG\Response(
     *         response="401",
     *         description="NOt authorize to complete this request",
     *     )     
     * )
     */
    public function login(Request $request){
        return user::login($request->all());
    }
    /**
     * @SWG\Get(
     *     path="/profile",
     *     summary="Profile User",
     *     tags={"UserController"},
     *     description="Profile of register user",
     *     operationId="profile",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="Enter your header token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     ),  
     *     @SWG\Response(
     *         response="401",
     *         description="NOt authorize to complete this request",
     *     )     
     * )
     */
    public function profile(Request $request){
        return response()->json(['status' => true,'message'=>'profile success','response'=>['profile'=>user::profile($request)]] , 200);
    }
    /**
     * @SWG\Get(
     *     path="/logout",
     *     summary="Logout User",
     *     tags={"UserController"},
     *     description="Logout user By this api",
     *     operationId="logout",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="Enter your header token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="NOt authorize to complete this request",
     *     )     
     * )
     */
    public function logout(Request $request){
        return user::logout($request);
    }
    /**
     * @SWG\Delete(
     *     path="/delete",
     *     summary="Delete User",
     *     tags={"UserController"},
     *     description="Delete user user profile from clicpic",
     *     operationId="deleteprofile",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="Enter your header token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     ),  
     *     @SWG\Response(
     *         response="401",
     *         description="NOt authorize to complete this request",
     *     )     
     * )
     */
    public function deleteProfile(Request $request){
        return user::deleteProfile($request);
    }
    /**
     * @SWG\Get(
     *     path="/confirm_phone",
     *     summary="Confirm Phone",
     *     tags={"UserController"},
     *     description="Confirm Phone Number if exit or not",
     *     operationId="confirm_phone",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Enter your phone number",
     *         required=true,
     *         type="number"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     ),  
     *     @SWG\Response(
     *         response="401",
     *         description="NOt authorize to complete this request",
     *     )     
     * )
     */
    public function confirm_phone(Request $request){
        return user::confirm_phone($request->all());
    }
    /**
     * @SWG\Put(
     *     path="/updateprofile",
     *     summary="Uodate Profile",
     *     tags={"UserController"},
     *     description="Update user profile",
     *     operationId="updateprofile",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="Enter your header token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="fullname",
     *         in="formData",
     *         description="Name of register user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="Email of register user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password of register user",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="country",
     *         in="formData",
     *         description="Country of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="city",
     *         in="formData",
     *         description="City of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="address",
     *         in="formData",
     *         description="Address of register user",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="camera",
     *         in="formData",
     *         description="Camera if yes 1 else 0",
     *         required=false,
     *         type="number"
     *     ),
     *     @SWG\Parameter(
     *         name="image",
     *         in="formData",
     *         description="Image of register user",
     *         required=true,
     *         type="file"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     ),  
     *     @SWG\Response(
     *         response="401",
     *         description="NOt authorize to complete this request",
     *     )     
     * )
     */
    public function updateprofile(Request $request){
        return user::updateprofile($request->all());
    }
}