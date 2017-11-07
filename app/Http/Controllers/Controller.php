<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="localhost",
 *     basePath="/clicpic/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="ClicPic",
 *         description="This api for clicpic.",
 *         termsOfService="http://localhost/clicpic/terms/",
 *         @SWG\Contact(
 *             email="example@clicpic.com"
 *         ),
 *         @SWG\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about clicpic",
 *         url="http://localhost/clicpic"
 *     )
 * )
 */

/**
 * @SWG\Tag(
 *   name="UserController",
 *   description="manage every thing in this controller register login profile logout deleteprofile, updateprofile",
 * ),
*/
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
