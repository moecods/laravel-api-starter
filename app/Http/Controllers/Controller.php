<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="mohammadeshaghi1998@gmail.com",
 *          name="mohammad eshaghi",
 *          url="https://backendcast.ir",
 *     ),
 * )
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @OA\Get(
     *     path="/api/hi",
     *     description="Home page",
     *     tags={"Home"},
     *     @OA\Response(response="200", description="An example resource")
     * )
     */

    public function index()
    {
        return response()->json(['hi' => 'hi']);
    }
}
