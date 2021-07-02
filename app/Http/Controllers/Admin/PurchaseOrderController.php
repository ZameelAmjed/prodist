<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\PurchaseOrder;
use App\PurchaseOrderItems;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class PurchaseOrderController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( Request $request ) {

		if ( ! Gate::allows( 'users_manage' ) ) {
			return abort( 401 );
		}

		$purchaseOrders = PurchaseOrder::where( 'status', $request->get( 'status', PurchaseOrder::pending ) )
		                               ->paginate( 10 );

		if ( $request->get( 'search' ) ) {
			$purchaseOrder = PurchaseOrder::where( DB::raw( 'CONCAT(\'PO\',LPAD(id,5,0))' ), $request->get( 'search' ) )->get();

			if ( $purchaseOrder->first() ) {
				return redirect()->route( 'admin.purchase_orders.show', $purchaseOrder->first()->id );
			} else {
				return view( 'admin.purchase_orders.index', compact( 'purchaseOrders' ) )
					->withErrors( trans( 'global.search_nothing_found' ) );
			}
		}

		return view( 'admin.purchase_orders.index', compact( 'purchaseOrders' ) );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		if ( ! Gate::allows( 'users_manage' ) ) {
			return abort( 401 );
		}
		$roles = Role::get()->pluck( 'name', 'name' );

		$suppliers = Supplier::all();

		return view( 'admin.purchase_orders.create', compact( 'roles', 'suppliers' ) );

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		if ( $request->get( 'bulk' ) ) {
			Validator::make( $request->all(), [
					'name.*'            => 'required',
					'requested_units.*' => 'required'
				]
			)->validate();


			$purchaseOrder = PurchaseOrder::create( [
				'supplier_id' => $request->get( 'supplier_id' ),
				'user_id'     => Auth::id(),
				'supplier_ref_code' => $request->get( 'supplier_ref_code' )
			] );

			for ( $i = 0; $i < count( request( 'name' ) ); $i ++ ) {
				$this->addPurchaseOrder(
					$request->get( 'name' )[ $i ],
					$request->get( 'requested_units' )[ $i ],
					$request->get( 'supplier_ref_code' ),
					$purchaseOrder
				);
			}

		} else {
			$request->validate( [
				'requested_units' => 'required'
			] );
			$purchaseOrder = $this->addPurchaseOrder( $request->get( 'product_id' ), $request->get( 'requested_units' ), $request->get( 'supplier_ref_code' ) );
		}


		return back()->with( 'success', trans( 'global.po_is_requested', [ 'id' => $purchaseOrder->uid ] ) );

	}


	private function addPurchaseOrder( $product_id, $requested_units, $refCode = null, $purchaseOrder = null ) {

		$product = Product::find( $product_id );

		if ( ! $purchaseOrder ) {
			$purchaseOrder = PurchaseOrder::create( [
				'supplier_id' => $product->supplier_id,
				'user_id'     => Auth::id(),
				'supplier_ref_code' => $refCode
			] );
		}

		PurchaseOrderItems::create( [
			'purchase_order_id' => $purchaseOrder->id,
			'product_id'        => $product->id,
			'requested_units'   => $requested_units
		] );
		return $purchaseOrder;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\PurchaseOrder $purchaseOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( PurchaseOrder $purchaseOrder ) {
		//
		if ( ! Gate::allows( 'users_manage' ) ) {
			return abort( 401 );
		}
		$roles = Role::get()->pluck( 'name', 'name' );

		return view( 'admin.purchase_orders.show', compact( 'roles', 'purchaseOrder' ) );


	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\PurchaseOrder $purchaseOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( PurchaseOrder $purchaseOrder, Request $request ) {
	}

	/**
	 * Update the specified resource in storage.
	 * GRN - Goods Received Note
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\PurchaseOrder $purchaseOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, PurchaseOrder $purchaseOrder ) {

		$request->validate( [
			'invoice_no'       => 'required',
			'received_units.*' => 'required',
			'expiry_date.*'    => 'nullable|date|after:today',
			'discount.*'       => 'nullable|numeric',
			'unit_price.*'       => 'required',
		] );

		$total_amount = 0;

		foreach ( $request->get( 'purchase_order_item_id' ) as $key => $value ) {

			$purchaseOrderItem = PurchaseOrderItems::find( $request->get( 'purchase_order_item_id' )[ $key ] );
			$purchaseOrderItem->received_units = $request->get( 'received_units' )[ $key ];
			$purchaseOrderItem->unit_price     = $request->get( 'unit_price' )[ $key ];

			if(!empty($request->get( 'expiry_date' )[ $key ]))
				$purchaseOrderItem->expiry_date = $request->get( 'expiry_date' )[ $key ];

			$purchaseOrderItem->discount       = $request->get( 'discount' )[ $key ];

			//Calculate Sub total price units*price *(discount)
			$purchaseOrderItem->total_price    = floatval($purchaseOrderItem->unit_price * $purchaseOrderItem->received_units) * ((100-$purchaseOrderItem->discount)/100);

			$purchaseOrderItem->save();

			$product = Product::find($purchaseOrderItem->product_id);
			$product->stock += $request->get( 'received_units' )[ $key ];
			$product->save();

			$total_amount += $purchaseOrderItem->total_price;
		}

		$purchaseOrder->status = PurchaseOrder::complete;
		$purchaseOrder->total_amount = $total_amount;
		$purchaseOrder->invoice_no = $request->get('invoice_no', 0);
		$purchaseOrder->batch_no = $request->get('batch_no', 0);
		$purchaseOrder->received_date = $request->get('received_date', Carbon::today()->format('Y-m-d'));
		$purchaseOrder->user_id = Auth::id();
		$purchaseOrder->save();

		//Send to Supplier Order View
		return redirect()->route('admin.purchase_orders.show', $purchaseOrder->id)->with( 'success', trans('global.grn').' '.trans( 'global.is_updated' ) );

	}

	public function grn( purchaseOrder $purchaseOrder ) {

		return view( 'admin.purchase_orders.grn', compact( 'purchaseOrder' ) );

	}


	public function quickgrn( purchaseOrder $purchaseOrder, Request $request){
		if(!($request->session()->get('secret')===$request->get('secret')))
			return abort(404);

		//mark all items recived
		$data['invoice_no'] = 'QGRN-'.$purchaseOrder->id;
		$i = 0;

		foreach ($purchaseOrder->purchaseOrderItems as $item){
			$data['purchase_order_item_id'][$i] = $item->id;
			$data['received_units'][$i] = $item->requested_units;
			$data['unit_price'][$i] = $item->product->distributor_price;
		}

		$newRequest = new Request();
		$newRequest->merge($data);

		$this->update($newRequest, $purchaseOrder);

		return redirect()->back()->with('success','GRN successfully created for the purchase order');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\PurchaseOrder $purchaseOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( PurchaseOrder $purchaseOrder ) {
	$purchaseOrder->status = PurchaseOrder::cancel;
	$purchaseOrder->save();

	return back()->with( 'alert', trans( 'global.po_is_cancelled', [ 'name' => $purchaseOrder->uid ] ) );
}



}
