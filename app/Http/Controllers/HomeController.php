<?php

namespace App\Http\Controllers;


use App\Order;
use App\Store;
use App\Http\Requests;
use App\Product;
use App\Http\Controllers\Admin\ReportsController;
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
	    

	    $reports = new ReportsController();
	    $gridData = $reports->dataOrdersSummery('array');
	    $count->orders = $gridData['orders'];
	    $count->payments = $gridData['payments'];
	    $count->purchaseOrders = $gridData['purchaseOrders'];
	    $count->totalStores = Store::count();
	    $count->totalInventory = Product::sum('stock');
	    $count->totalPendingOrders = Order::whereNotNull('delivery_date')->where('status','=','processing')->count();

	    return view('home', compact('count'));
    }

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function help()
	{
		return view('admin.help');
	}

	public function search(Request $request)
	{

		if($request->get('entity') == 'dealer'){
			$dealer = Dealers::where('name','=',$request->get('query'));
			redirect()->route('admin.dealers.show',$dealer);
		}

	}

}
