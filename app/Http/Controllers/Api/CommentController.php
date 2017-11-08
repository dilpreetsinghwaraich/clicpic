<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Comment;

class CommentController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/save_comment",
     *     summary="Save Comments",
     *     tags={"CommentController"},
     *     description="Save Comments on user according to user id",
     *     operationId="save_comment",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="Enter header token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="to_user_id",
     *         in="formData",
     *         description="Enter user_id which get comment",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="comment",
     *         in="formData",
     *         description="Enter Comment",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="rating",
     *         in="formData",
     *         description="Enter rating on user beetwen(0,5)",
     *         required=true,
     *         type="integer"
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
    public function save_comment(Request $request)
    {
        return Comment::save_comment($request->all());
    }
   
    /**
     * @SWG\Get(
     *     path="/get_comments",
     *     summary="Get Comment",
     *     tags={"CommentController"},
     *     description="Get All comments according to user id",
     *     operationId="get_comments",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Enter user_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     )    
     * )
     */
    public function get_comments(Request $request){
        if(empty($request->input('user_id')))
        {
            return response()->json(['status'=>false,'message'=>'User ID is required','response'=>''],400);
        }else
        {
            return response()->json(['status'=>false,'message'=>'Success','response'=>Comment::get_comments($request)],400);
        }
    } 
    /**
     * @SWG\Put(
     *     path="/approve_comment",
     *     summary="Approve Comment",
     *     tags={"CommentController"},
     *     description="Approve Comment with user id",
     *     operationId="approve_comment",     
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="Enter header token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="formData",
     *         description="Enter user_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         in="formData",
     *         description="Enter comment status(0 as unapprove, 1 as approve)",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Request completed successfully",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Missing parameter",
     *     )    
     * )
     */  
    public function approve_comment(Request $request){
          return Comment::approve_comment($request);
    } 
}