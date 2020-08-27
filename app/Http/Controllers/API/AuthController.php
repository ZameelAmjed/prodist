<?php
/**
 * Project: chint
 * File Name: AuthController.php
 * Author: Zameel Amjed
 * Date: 3/16/2020
 * Time: 10:09 AM
 */

namespace App\Http\Controllers\API;

use App\Electrician;
use App\Http\Requests\Admin\StoreElectricianRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Illuminate\Support\Facades\Auth;
use App\User;

use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;



class AuthController extends ResponseController
{
	use AuthenticatesUsers;
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login','signup']]);
	}
    //login
    public function login(Request $request)
    {
	    $validator = Validator::make($request->all(), [
		    'telephone' => 'required',
		    'nic' => 'required'
	    ]);

	    if($validator->fails()){
		    return $this->sendError($validator->errors());
	    }

	    $electrician = Electrician::where('nic','=',$request->input('nic'))->where('telephone','=',$request->input('telephone'))->first();
		if(!$electrician){
			return $this->sendError(['error' => 'Unauthorized']);
		}

	    // Get the token
	    $token = auth('api')->login($electrician);
	    //auth('api')->loginUsingId(1);
	    return $this->respondWithToken($token);
    }

    public function signup(Request $request)
    {
	    $validator = Validator::make($request->all(), [
		    'name' => 'required',
		    'nic' => 'required|unique:electricians,nic',
		    'telephone' => 'required|unique:electricians,telephone',
	    ]);

	    if($validator->fails()){
		    return $this->sendError($validator->errors());
	    }
	    $electrician = Electrician::create($request->all());

	    //new member code
	    $electrician->setMemberCode();

	    $token = auth('api')->login($electrician);
	    return $this->respondWithToken($token);
    }


	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout()
	{
		auth('api')->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth('api')->factory()->getTTL() * 60
		]);
	}


}