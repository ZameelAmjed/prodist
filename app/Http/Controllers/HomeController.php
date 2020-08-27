<?php

namespace App\Http\Controllers;

use App\Dealers;
use App\Electrician;
use App\Http\Controllers\Exports\EleImp;
use App\Http\Requests;
use App\Product;
use App\RewardsPayment;
use App\Setuprepo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$count = new Collection();
    	$count->electrician = Electrician::all()->count();
    	$count->dealers = Dealers::all()->count();
    	$count->products = Product::all()->count();
	    $payments = RewardsPayment::select(
		    DB::raw('sum(points) as sums'),
		    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey"))
	                   ->whereYear('created_at', date('Y'))
	                   ->groupBy('monthKey')
	                   ->orderBy('monthKey', 'ASC')
	                   ->get();
	    $count->monthlyPayment = [0,0,0,0,0,0,0,0,0,0,0,0];

	    foreach($payments as $payment){
		    $count->monthlyPayment[$payment->monthKey-1] = $payment->sums;
	    }

	    $pending_payments = Electrician::where('float_points','>=',10)->orderBy('float_points','desc')->take(6)->get();

	    $rewards = Setuprepo::take(10)->orderBy('created_at','desc')->get();

	    return view('home', compact('count','pending_payments','rewards'));
    }

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function electrician()
	{
		return view('home');
	}

	public function search(Request $request)
	{

		if($request->get('entity') == 'dealer'){
			$dealer = Dealers::where('name','=',$request->get('query'));
			redirect()->route('admin.dealers.show',$dealer);
		}

	}

}
