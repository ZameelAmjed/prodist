<?php

namespace App\Http\Controllers\Admin;

use App\Electrician;
use App\Http\Controllers\Exports\ElectricianExport;
use App\Http\Requests\Admin\StoreElectricianRequest;
use App\Http\Requests\Admin\UpdateElectricianRequest;
use App\PaymentRequest;
use App\RewardsPayment;
use App\Setuprepo;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Image;
use PDF;

class PaymentsController extends Controller
{

    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
	    $payments = RewardsPayment::paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
		$electrician = Electrician::find($request->input('electrician'));

        return view('admin.payments.create',compact('electrician'));
    }

    /**
     * Store a newly created User in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
		$electrician = Electrician::findOrFail($request->input('electrician_id'));
        $request->validate([
        	'points' => "required|numeric|min:1|max:$electrician->float_points",
        	'transfer_type' => 'required',
        	'payed_on' => 'required',
        ]);

	    if(@$electrician->paymentRequest->first()->status == 'pending'){
		    $paymentRequest = PaymentRequest::find($electrician->paymentRequest->first()->id);
		    $paymentRequest->status = 'complete';
		    $paymentRequest->save();
	    }

        RewardsPayment::create($request->all());

	    $electrician->float_points = abs(floatval(floatval($electrician->float_points)-$request->input('points'))) ;
	    $electrician->save();

        return redirect()
	        ->route('admin.electrician.show', compact('electrician'))->with('message',"Payment is updated for {$electrician->name}");
    }




	/**
	 * @param Request $request
	 *
	 * @return mixed
	 *
	 * Generate PDF to process payment
	 */
    public function generate(Request $request)
    {
    	$electrician = Electrician::find($request->get('id'));
    	$request->validate([
    		'payable_points' => 'numeric|min:1|max:'.$electrician->float_points
		    ]);
	    $attr = $request->all();

	    //Update or create payment request
	    if(@$electrician->paymentRequest->first()->status == 'pending'){
		    $paymentRequest = PaymentRequest::find($electrician->paymentRequest->first()->id);
		    $paymentRequest->amount = $request->get('payable_points');
		    $paymentRequest->save();
	    }else{
		    $paymentRequest = PaymentRequest::create(
			    ['electrician_id'=>$electrician->id,
			     'amount'=>$request->get('payable_points'),
			    ]);
	    }



	    $attr['uid'] = str_pad($paymentRequest->id,4,0,STR_PAD_LEFT);

	    $pdf =  PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
	              ->loadView('admin.payments.pdfpay', compact('electrician','attr'))
	              ->setPaper('a5', 'landscape');
	   return $pdf->download('payment-request-'.$attr['uid'].'.pdf');

    }

	/**
	 *
	 */
	public function checkRequests(Request $request)
	{
		$paymentRequests = PaymentRequest::where('status','pending')->paginate(15);
		return view('admin.payments.requests', compact('paymentRequests'));
	}


}
