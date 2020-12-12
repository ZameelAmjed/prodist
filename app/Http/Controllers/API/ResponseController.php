<?php
/**
 * Project: chint
 * File Name: ElectricianResponseController.php
 * Author: Zameel Amjed
 * Date: 3/16/2020
 * Time: 10:07 AM
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;



class ResponseController extends Controller
{
    public function sendResponse($response)
    {
	    return response()->json($response, 200);
    }


    public function sendError($error, $code = 404)
    {
	    $response = [
		    'error' => $error,
	    ];
	    return response()->json($response, $code);
    }


}