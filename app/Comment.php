<?php

namespace App;

use Validator, DateTime, DB, Hash, Request, File, Config, Helpers;
use Illuminate\Database\Eloquent\Model;
user App\User;
class Comment extends Models
{
    protected $table = 'comments';

    
    public static $commentrules = array(
        'user_id' => 'required|numeric',
        'comment' => 'required|min:30',
        'rating' => 'required|numeric|between:0,5',
         );
    protected function save_comment($request)
    {
        $validation = Validator::make($request, self::$commentrules);
        if($validation->passes()){
            $user = user::where('token',$request->header('token'))->first();
            $comment = new Comment;
            $comment->to_user_id    = (isset($request['to_user_id'])?$request['to_user_id']:'');
            $comment->from_user_id     = (isset($user->user_id)?$$user->user_id:'');
            $comment->comment     = (isset($request['comment'])?$request['comment']:'');
            $comment->rating     = (isset($request['rating'])?$request['rating']:0);            
            $comment->created_at   = new DateTime;
            $comment->updated_at   = new DateTime;
            $comment->save();
            if (!$comment) {
               return response()->json(['status'=>false,'message'=>'Something went wrong','response'=>''],400);
            }
            return response()->json(['status'=>true,'message'=>'Comment created successfully','response'=>''],201);
        } else {
            return response()->json(['status' => false, 'message' => $validation->getMessageBag()->first(),'response'=>''], 400);
        }
    }
    protected function get_comments($request)
    {
        return comment::where('user_id',$request['user_id'])->toArray();
    }
    protected function approve_comment($request)
    {
        if(empty($request->input('user_id')))
        {
            return response()->json(['status'=>false,'message'=>'User ID is required','response'=>''],400);
        }else
        {
            comment::where('user_id',$request->input('user_id'))->update(['status'=>$request->input('status')]);
            return response()->json(['status'=>true,'message'=>'Comment updated','response'=>''],200);
        }
    }
}
