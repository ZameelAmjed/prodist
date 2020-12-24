<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\MiscellaneousCharge;
use App\Order;
use App\Payment;
use App\Store;
use PDF;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Image;


class PaymentController extends Controller
{

    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$payment = Payment::select();

    	//Search query
    	if(request('search') )
    	{
		$payment = $payment->join('orders','payments.order_id','=','orders.id')
			->join('stores','orders.store_id','=','stores.id')
			->where(function($q){
				$q->where('stores.business_name','like','%'.request('search').'%')
				->orWhere('payments.cheque_no','=',request('search'));
			});
		}elseif(request('status') && request('status')!='all')
		{
		    $payment = $payment->where('payments.status', request('status',''));
	    }

	    $payments = $payment->orderby('payments.created_at','desc')->orderby('payments.cheque_no','desc')->paginate(15);

    	return view('admin.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    	return view('admin.payment.create');
    }

    /**
     * Store a newly created User in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	//if its a bulk payment handle here
    	if($request->get('bulk')){

    		 $request->validate([
		    	'store' => 'required',
			    'amount' => 'required',
			    'payment_type' => 'required'
	    	]);
    		$this->processBulkPayment($request->input('store'), $request->input('amount'), $request->all());

    		if($request->ajax()){
		    return response()->json([
			    'status'=>true,
			    'message'=>trans('global.payments_updated'),
		    ]);
	    }
    	}

    	//Non bulk payments being handled here
	    $request->validate([
	    	'order_id' => 'required',
		    'amount' => 'required',
		    'payment_type' => 'required'
	    ]);
		$order = Order::findOrFail($request->get('order_id'));

		$orderController = new OrderController();
	    $payment = $orderController->paymentReceived($order, $request->get('amount'), $request->all());

	    //if ajax send msg
	    if($request->ajax()){
		    return response()->json([
			    'status'=>true,
			    'message'=>trans('global.payments_updated'),
		    ]);
	    }
    }


    /**
    	Process Bulk Payments
    /**/
    private function processBulkPayment(int $store_id, float $payment_amount, array $paymentArray){
    	$orders = Order::where('status', 'processing')
    	->where('store_id',$store_id)
    	->orderby('orders.created_at','asc')
    	->get();
    
    	$orderController = new OrderController();

    	foreach ($orders as $key => $order) {
    		
    		if(!$payment_amount)
    			break;
    		
    		$due = ($order->total_amount - $order->return_amount) - $order->payments->sum('payment_amount');

    		if(!$due)
    			continue;

    		if($due < $payment_amount){
    			$payment = $orderController->paymentReceived($order, $due, $paymentArray);
    			$payment_amount = $payment_amount - $due;
    		}else{
    			$payment = $orderController->paymentReceived($order, $payment_amount, $paymentArray);
    			$payment_amount = 0;
    		}
    	}
    }


	/**
	 * @param Payment $payment
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function show(Payment $payment)
    {

    	return view('admin.payment.show', compact('payment'));
    }


    public function update(Payment $payment)
    {
	    //dishonored cheques
	    if(request('cheque_return')){

	    	//get all cheques with same cheque number
		    if(!empty($payment->cheque_no)){
			    $paymentsMadeByCheque = Payment::where('cheque_no',$payment->cheque_no)->where('realize_date',$payment->realize_date)->get();

			    foreach ($paymentsMadeByCheque as $paymentMade){
				    $paymentMade->status = Payment::reject;
				    $paymentMade->comment .= $paymentMade->cheque_no.' cheque returned';
				    $paymentMade->save();
				    //save charges

				    $order = $paymentMade->order;
				    $order->status = Order::$processing;
				    $order->save();
			    }
		    }else{
			    $payment->status = Payment::reject;
			    $payment->comment .= $payment->cheque_no.' cheque returned';
			    $payment->save();
			    //save charges

			    $order = $payment->order;
			    $order->status = Order::$processing;
			    $order->save();
		    }

		    if(request('cheque_return_charge')){
			    $missCharge = new MiscellaneousCharge();
			    $missCharge->order_id = $payment->order->id;
			    $missCharge->payment_id = $payment->id;
			    $missCharge->amount = request('cheque_return_charge');
			    $missCharge->comment = "dishonored cheque adjustment charge";
			    $missCharge->type = 'cheque_return_charge';
			    $missCharge->save();
		    }
		    return back()->with('success','Cheque return saved');
	    }
    }

	/**
	 * Print Bill for Invoice
	 * @param Payment $payment
	 * @param Request $request
	 *
	 * @return mixed
	 */
    public function receipt(Payment $payment, Request $request)
    {
	    $pdf =  PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
	               ->loadView('admin.payment.receipt',
		               compact('payment'))
	               ->setPaper('a5', 'portrait');
	    return $pdf->download($payment->order->uid.'-'.$payment->id.'.pdf');
    }

	/**
	 * @param MiscellaneousCharge $miscellaneousCharge
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function returnCharge(MiscellaneousCharge $miscellaneousCharge, Request $request)
	{
    	$payment = Payment::find($miscellaneousCharge->payment_id);
		$pdf =  PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
		           ->loadView('admin.payment.returncharge',
			           compact('miscellaneousCharge','payment'))
		           ->setPaper('a5', 'portrait');
		return $pdf->download($payment->order->uid.'-additional_charges'.$miscellaneousCharge->id.'.pdf');
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function returnChargeView()
	{
		$miscellaneousCharge = MiscellaneousCharge::paginate(15);

		return view('admin.payment.returncharge_index', compact('miscellaneousCharge'));
	}
}
