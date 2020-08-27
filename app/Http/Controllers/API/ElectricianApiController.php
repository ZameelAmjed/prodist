<?php
/**
 * Project: chint
 * File Name: AuthController.php
 * Author: Zameel Amjed
 * Date: 3/16/2020
 * Time: 10:09 AM
 */

namespace App\Http\Controllers\API;

use App\Duplicates;
use App\Electrician;
use App\Http\Controllers\Traits\ProductTrait;
use App\Http\Requests\Admin\StoreElectricianRequest;
use App\Product;
use App\Setuprepo;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Illuminate\Support\Facades\Auth;
use App\User;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;



class ElectricianApiController extends ResponseController
{
	use ProductTrait;
	public function __construct()
	{
		$this->middleware('auth:api');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 * APP Edit for Electrician
	 */
	public function edit(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'block'=>'required',
			'language'=>'in:english,tamil,sinhala',
			'street'=>'required',
			'city'=>'required',
			'date_of_birth'=>'required'
		]);

		if($validator->fails()){
			return $this->sendError($validator->errors());
		}

		$electrician = Electrician::select('name','block','language','street','city','date_of_birth')->find(auth('api')->id());
		$electrician->name = $request->input('name');
		$electrician->block = $request->input('block');
		$electrician->language = $request->input('language');
		$electrician->street = $request->input('street');
		$electrician->city = $request->input('city');
		$electrician->date_of_birth = $request->input('date_of_birth');
		$electrician->save();

		return response()->json($electrician, 200);

	}

	public function getElectrician(Request $request){
		$payload = auth('api')->payload();
		$electrician = Electrician::find($payload->get('sub'));
		return response()->json($electrician, 200);
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addPoints(Request $request){

		$validator = Validator::make($request->all(), [
			'barcode' => 'required',
		]);

		if($validator->fails()){
			return $this->sendError($validator->errors());
		}

		$res = $this->validateBarcode($request->input('barcode'));

		$payload = auth('api')->payload();
		$electrician = Electrician::find($payload->get('sub'));

		if($res['status']){
			//get the points
			$rewards = new Setuprepo();
			$rewards->barcode = $request->input('barcode');
			$rewards->electrician = $electrician->id;
			$rewards->product = $res['data']->id;
			$rewards->model = $res['data']->model;
			$rewards->points = $res['data']->points;
			$rewards->user = '0';
			$rewards->ip = $request->ip();
			$rewards->save();
			//save points in electrician
			$electrician->points = $electrician->points + floatval($res['data']->points);
			$electrician->float_points = $electrician->float_points + floatval($res['data']->points);
			$electrician->products_count = $electrician->products_count +1;
			$electrician->save();

			$product = Product::find($rewards->product);
			$product->units_active = $product->units_active + 1;
			$product->last_barcode = $request->input('barcode');
			$product->save();

		}else{
			//log for error
			$duplicates = new Duplicates();
			$duplicates->barcode = $request->input('barcode');
			$duplicates->electrician = $payload->get('sub');
			$duplicates->user = 0;
			$duplicates->ip = $request->ip();
			$duplicates->save();


		}
		return response()->json($res,422);


	}


	public function getProducts(){
		$payload = auth('api')->payload();
		$electrician = Electrician::find($payload->get('sub'));
		return response()->json([
			'rewards'=>$electrician->getRewards()->toArray(),
			'payments'=>$electrician->payments()->get()->toArray()
		]);
	}



}