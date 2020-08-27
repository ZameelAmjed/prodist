<?php

namespace App\Http\Controllers\Admin;

use App\Duplicates;
use App\Electrician;

use App\Http\Controllers\Traits\ProductTrait;
use App\Http\Requests\Admin\StoreRewardsRequest;

use App\Product;
use App\RewardsPayment;
use App\Setuprepo;
use App\User;


use function Complex\negative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use PDF;
use DNS1D;
use DNS2D;

class RewardsController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    use ProductTrait;

    public function index(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        if($request->input('nic',false)){
	        $electrician = Electrician::where('nic', '=', $request->input('nic'))->Orwhere('telephone', '=', $request->input('nic'))
		        ->first();
	        if(isset($electrician)){
		        return view('admin.rewards.index', compact('electrician'));
	        }else{
		        return view('admin.rewards.index')->withErrors(['nic' => ['Electrician Not Found']]);
	        }
        }
	    return view('admin.rewards.index');


    }

    public function getPoints(Request $request)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

		$request->validate([
			'barcode'=>'required'
		]);

	    return response()->json($this->validateBarcode($request->input('barcode')));
	    	//send points and product info

    }

    /**
     * Update Product in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRewardsRequest $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
		//check if elecetrician is presence
	    $data = [];
	    if($request->input('electrician')){
		    $barcodes = $request->input('barcodes');
        	foreach ($barcodes as $val){
        		$res = $this->validateBarcode($val);
		        array_push($data, $res);
        		if($res['status']){
					//get the points
			        $rewards = new Setuprepo();
			        $rewards->barcode = $val;
			        $rewards->electrician = $request->input('electrician');
			        $rewards->product = $res['data']->id;
			        $rewards->model = $res['data']->model;
			        $rewards->points = $res['data']->points;
			        $rewards->user = auth()->id();
			        $rewards->ip = $request->ip();
			        $rewards->save();
			        //save points in electrician
			        $electrician = Electrician::find($request->input('electrician'));
			        $electrician->points = $electrician->points + floatval($res['data']->points);
			        $electrician->float_points = $electrician->float_points + floatval($res['data']->points);
			        $electrician->products_count = $electrician->products_count +1;
			        $electrician->save();

			        $product = Product::find($rewards->product);
			        $product->units_active = $product->units_active + 1;
			        $product->last_barcode = $val;
			        $product->save();

		        }else{
        			//log for error
			        $duplicates = new Duplicates();
			        $duplicates->barcode = $val;
			        $duplicates->electrician = $request->input('electrician');
			        $duplicates->user = auth()->id();
			        $duplicates->ip = $request->ip();
			        $duplicates->save();

        			return response()->json($data,422);
		        }
	        }
	    }
        return response()->json($data);
    }

    public function show()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

	    return view('admin.products.show', compact('product'));
    }

	public function check(Request $request)
	{
		if (! Gate::allows('users_manage')) {
			return abort(401);
		}

		$barcode = [];
		if($request->get('barcode')){
			$barcode = $this->validateBarcode($request->get('barcode'));
			//get more info if barcode available
			if(isset($barcode['used'])){
				$barcode['rewardData'] = Setuprepo::where('barcode','=',$request->get('barcode'))->get();
			}
		}
		return view('admin.rewards.check', compact('barcode'));
	}

    /**
     * Remove Productfrom storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

    	$request->validate([
    		'barcode'=>'required'
	    ]);

	    $barcode = Setuprepo::where('barcode','=',$request->input('barcode'))->get()->first();

	    if(!$barcode->electrician)
	    	return back()->withErrors(['message'=>'Error barcode not found']);

	    $rewardsReduction = new RewardsPayment();
	    $rewardsReduction->electrician_id = $barcode['electrician'];
	    $rewardsReduction->points = -($barcode['points']);
	    $rewardsReduction->transfer_type = RewardsPayment::REDUCTION;
	    $rewardsReduction->comment = 'REDUCTION MADE ON POINTS BY ADMIN';
	    $rewardsReduction->confirmation_message = 'Not Sent';
	    $rewardsReduction->payed_on = date('Y-m-d');
	    $rewardsReduction->save();

	    $electrician = Electrician::find($rewardsReduction->electrician_id);
	    $electrician->products_count = $electrician->products_count -1;
	    $electrician->points = $electrician->points + $rewardsReduction->points;
	    $electrician->float_points = $electrician->float_points + $rewardsReduction->points;
	    $electrician->save();

	    $barcode->delete();
    	//If nothing under this is available delete
	    //'','','','','',''
        //$product->delete();

        return redirect()->route('admin.electrician.show',$electrician);
    }



    public function bulkAdd(Request $request)
    {
		$electrician = Electrician::find($request->get('id'));

		return view('admin.rewards.show_bulk',compact('electrician'));
    }

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * Bulk Barcode Save
	 */
	public function bulkStore(Request $request){

		$request->validate([
			'id' => 'required',
		]);

		$electrician = Electrician::find($request->input('id'));
		$totalPoints = 0;
		$totalItems = 0;
		foreach ($request->input('product') as $item){
			$dbproduct = Product::find($item["product"]["_id"]);

			$rewards = new Setuprepo();
			$rewards->barcode = uniqid('BLK');
			$rewards->electrician = $electrician->id;
			$rewards->product = $dbproduct->id;
			$rewards->model = $dbproduct->model;
			$rewards->points = floatval($dbproduct->points * $item["qty"]);
			$rewards->user = '0';
			$rewards->ip = $request->ip();
			$rewards->save();
			$totalPoints += floatval($rewards->points);
			$totalItems +=  $item["qty"];
		}

		//save points in electrician
		$electrician->points = $electrician->points + floatval($totalPoints);
		$electrician->float_points = $electrician->float_points + floatval($totalPoints);
		$electrician->products_count = $electrician->products_count + $totalItems;
		$electrician->save();

		return response()->json(['status'=>'success']);
	}




}
