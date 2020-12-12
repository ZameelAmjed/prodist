<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\Supplier;
use App\SupplierOrder;
use App\SupplierOrderItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class SupplierOrderController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( Request $request ) {

		if ( ! Gate::allows( 'users_manage' ) ) {
			return abort( 401 );
		}

		$supplierOrders = SupplierOrder::where( 'status', $request->get( 'status', SupplierOrder::pending ) )
		                               ->paginate( 10 );

		if ( $request->get( 'search' ) ) {
			$supplierOrder = SupplierOrder::where( DB::raw( 'CONCAT(\'PO\',LPAD(id,5,0))' ), $request->get( 'search' ) )->get();

			if ( $supplierOrder->first() ) {
				return redirect()->route( 'admin.supplier_order.show', $supplierOrder->first()->id );
			} else {
				return view( 'admin.supplier_order.index', compact( 'supplierOrders' ) )
					->withErrors( trans( 'global.search_nothing_found' ) );
			}
		}

		return view( 'admin.supplier_order.index', compact( 'supplierOrders' ) );
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

		return view( 'admin.supplier_order.create', compact( 'roles', 'suppliers' ) );

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


			$supplierOrder = SupplierOrder::create( [
				'supplier_id' => $request->get( 'supplier_id' ),
				'user_id'     => Auth::id()
			] );

			for ( $i = 0; $i < count( request( 'name' ) ); $i ++ ) {
				$this->addSupplierOrder(
					$request->get( 'name' )[ $i ],
					$request->get( 'requested_units' )[ $i ],
					$supplierOrder
				);
			}

		} else {
			$request->validate( [
				'requested_units' => 'required'
			] );
			$supplierOrder = $this->addSupplierOrder( $request->get( 'product_id' ), $request->get( 'requested_units' ) );
		}


		return back()->with( 'success', trans( 'global.po_is_requested', [ 'id' => $supplierOrder->uid ] ) );

	}


	private function addSupplierOrder( $product_id, $requested_units, $supplierOrder = null ) {

		$product = Product::find( $product_id );

		if ( ! $supplierOrder ) {
			$supplierOrder = SupplierOrder::create( [
				'supplier_id' => $product->supplier_id,
				'user_id'     => Auth::id()
			] );
		}

		SupplierOrderItems::create( [
			'supplier_order_id' => $supplierOrder->id,
			'product_id'        => $product->id,
			'requested_units'   => $requested_units
		] );
		return $supplierOrder;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\SupplierOrder $supplierOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( SupplierOrder $supplierOrder ) {
		//
		if ( ! Gate::allows( 'users_manage' ) ) {
			return abort( 401 );
		}
		$roles = Role::get()->pluck( 'name', 'name' );

		return view( 'admin.supplier_order.show', compact( 'roles', 'supplierOrder' ) );


	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\SupplierOrder $supplierOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( SupplierOrder $supplierOrder, Request $request ) {
	}

	/**
	 * Update the specified resource in storage.
	 * GRN - Goods Received Note
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\SupplierOrder $supplierOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, SupplierOrder $supplierOrder ) {

		$request->validate( [
			'invoice_no'       => 'required',
			'received_units.*' => 'required',
			'expiry_date.*'    => 'nullable|date|after:today',
			'discount.*'       => 'nullable|numeric',
			'unit_price.*'       => 'required',
		] );

		$total_amount = 0;

		foreach ( $request->get( 'supplier_order_item_id' ) as $key => $value ) {

			$supplierOrderItem = SupplierOrderItems::find( $request->get( 'supplier_order_item_id' )[ $key ] );
			$supplierOrderItem->received_units = $request->get( 'received_units' )[ $key ];
			$supplierOrderItem->unit_price     = $request->get( 'unit_price' )[ $key ];

			if(!empty($request->get( 'expiry_date' )[ $key ]))
				$supplierOrderItem->expiry_date = $request->get( 'expiry_date' )[ $key ];

			$supplierOrderItem->discount       = $request->get( 'discount' )[ $key ];

			//Calculate Sub total price units*price *(discount)
			$supplierOrderItem->total_price    = floatval($supplierOrderItem->unit_price * $supplierOrderItem->received_units) * ((100-$supplierOrderItem->discount)/100);

			$supplierOrderItem->save();

			$product = Product::find($supplierOrderItem->product_id);
			$product->stock += $request->get( 'received_units' )[ $key ];
			$product->save();

			$total_amount += $supplierOrderItem->total_price;
		}

		$supplierOrder->status = SupplierOrder::complete;
		$supplierOrder->total_amount = $total_amount;
		$supplierOrder->invoice_no = $request->get('invoice_no', 0);
		$supplierOrder->batch_no = $request->get('batch_no', 0);
		$supplierOrder->received_date = $request->get('received_date', Carbon::today()->format('Y-m-d'));
		$supplierOrder->user_id = Auth::id();
		$supplierOrder->save();

		//Send to Supplier Order View
		return redirect()->route('admin.supplier_order.show', $supplierOrder->id)->with( 'success', trans('global.grn').' '.trans( 'global.is_updated' ) );

	}

	public function grn( supplierOrder $supplierOrder ) {

		return view( 'admin.supplier_order.grn', compact( 'supplierOrder' ) );

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\SupplierOrder $supplierOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( SupplierOrder $supplierOrder ) {
	$supplierOrder->status = SupplierOrder::cancel;
	$supplierOrder->save();

	return back()->with( 'alert', trans( 'global.po_is_cancelled', [ 'name' => $supplierOrder->uid ] ) );
}



}
