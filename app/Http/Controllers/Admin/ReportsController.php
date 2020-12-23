<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Exports\ElectricianExport;
use App\Http\Controllers\Exports\ProductsExport;
use App\Payment;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;


class ReportsController extends Controller
{
	public function index(){
		return view('admin.reports.index');
	}

	/**
	Report: Stores
	**/
	public function getStores(Request $request)
	{
		$query = DB::table('stores');

		//Top buyers
		if(request('buyers')){

			$query->join('orders', function ($join){
				$join->on('stores.id','=','orders.store_id')
				->where('orders.status','=','complete');
			})
			->select(
				'stores.id',
				'stores.business_name',
				'stores.owner_name',
				'stores.rating',
				'stores.credit_period',
				'stores.telephone',
				'stores.area',
				'stores.region',
				'stores.created_at',
				 DB::raw('SUM(orders.total_amount - orders.return_amount) as total')
				)
			->groupBy(
				'stores.id',
				'stores.business_name',
				'stores.owner_name',
				'stores.rating',
				'stores.credit_period',
				'stores.telephone',
				'stores.area',
				'stores.region',
				'stores.created_at'
			)
			->orderBy('total','desc');
			$stores = $query->paginate(100);
		return view('admin.reports.stores', compact('stores'));
		}

		if($request->input('city'))
		{
			$query->where('city', '=',$request->input('city'));
		}

		if($request->input('ratings'))
		{
				$query->orderBy('rating',$request->input('ratings'));
		}

		if($request->get('export'))
		{
			//download excel
			return Excel::download(new ElectricianExport($query), 'Stores-'.date('Y-m-d').'.xlsx');
		}

		$stores = $query->paginate(100);
		return view('admin.reports.stores', compact('stores'));
	}

	public function getPayments(Request $request)
	{
		$query = DB::table('payments')
		->join('orders','payments.order_id','=','orders.id')
		->join('stores','orders.store_id','=','stores.id');

		if(request('business_name'))
		{
			$query->where('business_name','like','%'.request('business_name').'%');
		}

		if(request('invoice_no'))
		{
			$query->where('invoice_no','=', ltrim(ltrim(request('invoice_no'), 'INV'),0));
		}

		if(request('payment_type'))
		{
			$query->where('payments.payment_type','=', request('payment_type'));
		}

		//Date Range only if set 
		if(request('date_start')&&request('date_end')){
			$query->whereBetween('payments.created_at',[request('date_start'), request('date_end')]);
		}

		if(request('cheque_sort')=='float')
		{
			//get float cheques
			$query->whereDate('payments.realize_date', '>=', date('y-m-d'));
		}

		if(request('cheque_sort')=='reject'){
			$query->where('payments.status','reject');
		}else{
			$query->where('payments.status','accept');
		}

		$query->select('orders.invoice_no','stores.business_name','payments.payment_amount','payments.payment_type', 'payments.created_at','payments.realize_date');

		$payments = $query->paginate(100);

		//Get Total Value
		$query->select(DB::raw('SUM(payments.payment_amount) as total'));
		$sum = $query->get()->pluck('total');
		$total = ($sum) ? $sum[0] :0;

		return view('admin.reports.payments',compact('payments','total'));
	}

	public function getDuePayments(Request $request){
		//USING COMMON TEMPLATE
		$query = DB::table('orders')
		->join('payments',function($join){
			$join->on('orders.id','=','payments.order_id')->where('payments.status','=','accept');
		})
		->join('stores','orders.store_id','=','stores.id')
		->select(
			'orders.invoice_no as order.invoice_id',
			'stores.business_name as store.business_name',
			'orders.total_amount as order.total_amount',
			'orders.return_amount as order.return_amount',
			DB::raw('(SUM(payments.payment_amount)) as payment_amount'),
			DB::raw('((orders.total_amount - orders.return_amount) - SUM(payments.payment_amount)) as due_amount')
		)
		->where('orders.status','=','processing');
		
		//Date Range only if set 
		if(request('date_start')&&request('date_end')){
			$query->whereBetween('orders.created_at',[request('date_start'), request('date_end')]);
		}

		if(request('name'))
		{
			$query->where('business_name','like','%'.request('name').'%');
		}


		$query->groupby('orders.invoice_no','stores.business_name','orders.total_amount','orders.return_amount');
		$items = $query->paginate(10);
		$info['pagename'] = 'Due Payments';

		return view('admin.reports.common',compact('items','info'));
	}

	/**
	* Report: Inventory
	**/
	public function getInventory(Request $request)
	{

		
		$query = DB::table('products')
		->leftjoin('supplier_order_items','products.id','=','supplier_order_items.product_id')
		->leftjoin('supplier_orders', function($join){
			$join->on('supplier_order_items.supplier_order_id','=','supplier_orders.id')
			->where('supplier_orders.status','received');
		})
		
		->join('suppliers','products.supplier_id','=','suppliers.id')
		->select('products.id','products.code','products.name', 'products.stock','supplier_order_items.requested_units','supplier_order_items.received_units','products.supplier_id','suppliers.name AS supplier_name');

		if($request->input('code'))
		{

			$query->where('code', '=',$request->input('code'))->orWhere('products.name','=',$request->input('code'));
		}

		if(request('supplier_name'))
		{
			$query->where('suppliers.name', 'like','%'.request('supplier_name').'%');
		}

		//Sort by Stock or Requested Units
		if($sort = explode(',',request('sort')))
			$query->orderBy($sort[0],$sort[1]);
			
		

		if($request->get('export'))
		{
			//download excel
			return Excel::download(new ElectricianExport($query), 'Inventory-'.date('Y-m-d').'.xlsx');
		}

		$products = $query->paginate(100);
		
		return view('admin.reports.inventory', compact('products'));
	}



    public function getProducts(Request $request)
    {
    	if(request('sort'))
    	{
    		//Sort according to sales
    		$sort = explode(',', request('sort'));
    		
    		$query = DB::table('order_items')
    		->select('products.id','products.code','products.name', 'products.brand','suppliers.name AS supplier_name',DB::raw('SUM(order_items.qty) as qty'))
    		->join('orders',function($join){
    			$join->on('order_items.order_id','=','orders.id')
				->where('orders.status','complete');
    		})
    		->join('products','order_items.product_id','=','products.id')
    		->join('suppliers','products.supplier_id','=','suppliers.id');

    		 if($request->input('code'))
		{
			$query->where('code', '=',$request->input('code'));
		}

		if(request('name'))
		{
			$query->where('products.name', 'like','%'.request('name').'%');
		}

		if(request('supplier_name'))
		{
			$query->where('suppliers.name', 'like','%'.request('supplier_name').'%');
		}
			
    		$query->groupby('products.id','products.code','products.name', 'products.brand', 'suppliers.name')
    		->orderby('qty',$sort[1]);

    		if($request->get('export'))
	    {
		    //download excel
		    return Excel::download(new ProductsExport($query), 'Products-'.date('Y-m-d').'.xlsx');
	    }
    	$products = $query->paginate(100);

	    return view('admin.reports.products', compact('products'));
    	}

	    $query = DB::table('products')
	    	->join('suppliers','products.supplier_id','=','suppliers.id')
	    	->select('products.id','products.code','products.name', 'products.brand','suppliers.name AS supplier_name');


	    if($request->input('code'))
		{
			$query->where('code', '=',$request->input('code'));
		}

		if(request('name'))
		{
			$query->where('products.name', 'like','%'.request('name').'%');
		}

		if(request('supplier_name'))
		{
			$query->where('suppliers.name', 'like','%'.request('supplier_name').'%');
		}
			

	    if($request->get('export'))
	    {
		    //download excel
		    return Excel::download(new ProductsExport($query), 'Products-'.date('Y-m-d').'.xlsx');
	    }

	    $products = $query->paginate(100);

	    return view('admin.reports.products', compact('products'));
    }

    




	public function getOrders(Request $request)
	{

		$query = DB::table('orders');

		$query->select(
			DB::raw('CONCAT("INV",LPAD(orders.invoice_no, 7, 0)) as invoice_no'),
			'stores.business_name',
			'orders.payment_type',
			'orders.total_amount',
			'orders.return_amount',
			'orders.status',
			'orders.delivery_date',
			DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") as created_at'),
			DB::raw('DATE_FORMAT(orders.updated_at, "%Y-%m-%d") as updated_at')
		);
		$query->join('stores','orders.store_id','=','stores.id');
		$query->where('invoice_no','!=',null);
		
		if(request('name')){
			$query->where('invoice_no','=',preg_replace("/INV/", '',request('name')));
		}

		if(request('deliveries')){
			$query->where('orders.status','!=','pending');
			$query->whereDate('orders.delivery_date','>=',date('y-m-d'));
			$query->orderby('orders.delivery_date','ASC');
		}

		if(request('pending')){
			$query->where('orders.status','=','pending');
		}
		
		$items = $query->paginate(100);

		$info['name'] = 'Invoice No';
		$info['pagename'] = trans('cruds.order.title_singular');


		return view('admin.reports.common', compact('items'),compact('info'));
	}


	public function getSupplierOrders(Request $request)
	{

		$query = DB::table('supplier_orders');

		$query->join('suppliers','supplier_orders.supplier_id','=','suppliers.id');

		$query->select(
			DB::raw('CONCAT("",",","supplier_order/",supplier_orders.id) as link'),
			'supplier_orders.invoice_no as supplier_invoice_no',
			'supplier_orders.batch_no as supplier_batch_no',
			'suppliers.name as supplier_name',
			'supplier_orders.status as status',
			DB::raw('DATE_FORMAT(supplier_orders.created_at, "%Y-%m-%d") as created_at'),
			DB::raw('FORMAT(supplier_orders.total_amount, 2) as total_amount')
		);

		if(request('name')){
			$query->where('suppliers.name','like','%'.request('name').'%');
		}

		if(request('status')){
			$query->where('supplier_orders.status','=',request('status'));
		}

		if(request('supplier_batch_no')){
			$query->where('supplier_orders.batch_no','=',request('supplier_batch_no'));
		}

		if(request('supplier_invoice_no')){
			$query->where('supplier_orders.invoice_no','=',request('supplier_invoice_no'));
		}
		
		$items = $query->paginate(100);

		//get total
		$query->select(
			DB::raw('SUM(supplier_orders.total_amount) as total_amount')
		);
		
		$total = $query->value('total_amount');

		$info['form'] = 'form_suppliers';
		$info['pagename'] = trans('cruds.supplier.title_singular');

		return view('admin.reports.common', compact('items','info','total'));
	}


	public function getStoresDuePayments(Request $request)
	{
		//USING COMMON TEMPLATE
		$query = DB::table('orders')
		           ->select(
			           DB::raw('CONCAT("",",","orders/",orders.id) as link'),
			           'stores.business_name',
			                DB::raw('CONCAT("ORD",LPAD(orders.id,8,0)) as order_id'),
		           	        'orders.delivery_date',
		                    'stores.credit_period',
		                    DB::raw('SUM(payments.payment_amount) as total_payment'),
			                DB::raw('@diff:=DATEDIFF(NOW(), orders.delivery_date) AS duration')
			           )
			->LeftJoin('payments',function($join){
				$join->on('orders.id','=','payments.order_id')
				     ->where('payments.status','=','accept');
			})
			->join('stores','orders.store_id','=','stores.id')
			->where('orders.status','=','processing')
			->whereNotNull('orders.delivery_date')
			->where(DB::raw('@diff>stores.credit_period'))
			->groupby('orders.id','stores.business_name','orders.delivery_date','stores.credit_period')
			->orderby('duration','DESC');
		$items = $query->paginate(100);

		$info['form'] = 'noform';
		$info['pagename'] = 'Stores Long Due';

		return view('admin.reports.common',compact('items', 'info'));

	}
	/**
	 * Orders Summery Dashboard
	 * @param string $type
	 *
	 * @return array|Collection
	 */
	public function dataOrdersSummery($type = 'collection'){

		$count = new Collection();
		$count->orders = DB::table('orders')->select(
		    DB::raw('sum(total_amount) as total'),
		    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey"))
		->where('status','!=','pending')
	                   ->whereYear('created_at', date('Y'))
	                   ->groupBy('monthKey')
	                   ->orderBy('monthKey', 'ASC')
	                   ->get();


       $count->payments = DB::table('payments')->select(
		    DB::raw('sum(payment_amount) as total'),
		    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey"))
       					->where('status', 'accept')
       					->where((function($query) {
                $query->orWhere('realize_date', '<',date('Y-m-d'))
                      ->orWhere('realize_date', '=', null);
            }))
	                   ->whereYear('created_at', date('Y'))
	                   ->groupBy('monthKey')
	                   ->orderBy('monthKey', 'ASC')
	                   ->get();

       $count->supplierOrders = DB::table('supplier_orders')->select(
		    DB::raw('sum(total_amount) as total'),
		    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey"))
       					->where('status', 'received')
	                   ->whereYear('created_at', date('Y'))
	                   ->groupBy('monthKey')
	                   ->orderBy('monthKey', 'ASC')
	                   ->get();
	    if($type == 'collection')
	    	return $count;
	    
	    //preocess array
	    $orders = $payments = $supplierOrders = [0,0,0,0,0,0,0,0,0,0,0,0];

	    foreach($count->orders as $order){
		    $orders[$order->monthKey-1] = number_format($order->total,2,'.','');
	    }

	    foreach($count->payments as $payment){
		    $payments[$payment->monthKey-1] = number_format($payment->total,2,'.','');
	    }

	    foreach($count->supplierOrders as $supplierOrder){
		    $supplierOrders[$supplierOrder->monthKey-1] = number_format($supplierOrder->total,2,'.','');
	    }

	    return ['orders'=>$orders, 'payments'=>$payments, 'supplierOrders'=>$supplierOrders];

	}

	/**
	 * Get Float Cheques - JSON Dashboard tab
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getFloatCheque(Request $request){
		$data = Payment::select('payments.id', 'stores.business_name as name', 'realize_date', 'payment_amount as amount')
		->join('orders','payments.order_id','=','orders.id')
		->join('stores','orders.store_id','=','stores.id')
		->where('payments.status',Payment::accept)
		               ->where('realize_date','>=',date('Y-m-d'))
		               ->paginate(5);
		return response()->json($data);
	}

	/**
	 * Get Dishonored Cheques - JSON Dashboard tab
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getDishonoredCheque(Request $request){
		$data = Payment::select('payments.id', 'stores.business_name as name', 'realize_date', 'payment_amount as amount')
		               ->join('orders','payments.order_id','=','orders.id')
		               ->join('stores','orders.store_id','=','stores.id')
		               ->where('payments.status',Payment::reject)
		               ->paginate(5);
		return response()->json($data);
	}
}
