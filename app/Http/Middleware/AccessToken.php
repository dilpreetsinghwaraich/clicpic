<?php
namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\user;
class AccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty($request->header('token')))
        {
            return response()->json(['status'=>true,'message'=>'Token should not be empty','response'=>''],401);
        }
        if(empty(user::where('token',$request->header('token'))->first()))
        {
            return response()->json(['status'=>true,'message'=>'Token expired! Please login to continue!','response'=>''],401);
        }        
       return $next($request);
    }
}