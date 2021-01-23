<?php
/**
 * Project: Prodist
 * File Name: AuthController.php
 * Author: Zameel Amjed
 * Date: 3/16/2020
 * Time: 10:09 AM
 */

namespace App\Http\Controllers\API;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Illuminate\Support\Facades\URL;
use JWTAuth;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;



class AuthController extends ResponseController
{
	use AuthenticatesUsers;
	public function __construct()
	{
		$this->middleware('auth:api',
			['except' => ['login','signup']]);
	}
    //login
    public function login(Request $request)
    {
	    $validator = Validator::make($request->all(), [
		    'email' => 'required',
		    'password' => 'required'
	    ]);

	    if($validator->fails()){
		    return $this->sendError($validator->errors());
	    }
		try{
			if(! $token = JWTAuth::attempt($request->all())){
				return $this->sendError(['error' => 'invalid_credentials'],401);
			}else{
				return $this->respondWithToken($token);
			}
		}catch (JWTException $e){
			return $this->sendError(['error' => 'could_not_create_token'],508);
		}


    }

    //This Not required as POS do not register new users
    public function signup(Request $request)
    {
    }


    public function profile(Request $request){
		$user = User::find(auth()->id());
		return response()->json([
			'data'=>[
				'user_id' => $user->id,
				'name' => $user->name,
				'avatar' => ($user->photo)? URL::to('/images/users/'.$user->photo) : URL::to('/images/users/profile.png')
			]
		]);
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
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh()
	{
		$token = auth('api')->refresh();
		return $this->respondWithToken($token);
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
			'data'=>[
				'access_token' => $token,
				'token_type' => 'bearer',
				'expires_in' => auth('api')->factory()->getTTL() * 60
			]
		]);
	}


}