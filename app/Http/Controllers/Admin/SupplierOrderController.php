<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\Supplier;
use App\SupplierOrder;
use App\SupplierOrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class SupplierOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    $supplierOrders = SupplierOrder::where('status',$request->get('status',SupplierOrder::pending))
	                                   ->paginate(10);

	    if($request->get('search')){
		   $supplierOrder = SupplierOrder::where(DB::raw('CONCAT(\'PO\',LPAD(id,5,0))'),$request->get('search'))->get();

		    if($supplierOrder->first()){
		    	return redirect()->route('admin.supplier_order.show',$supplierOrder->first()->id);
		    }else{
			    return view('admin.supplier_order.index', compact('supplierOrders' ))
			    ->withErrors(trans('global.search_nothing_found'));
		    }
	    }

	    return view('admin.supplier_order.index', compact('supplierOrders' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }
	    $roles = Role::get()->pluck('name', 'name');
	    
		$suppliers = Supplier::all();

	    return view('admin.supplier_order.create', compact('roles','suppliers'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if($request->get('bulk'))
		{
			Validator::make($request->all(),[
					'name.*'=>'required',
					'requested_units.*' => 'required'
				]
			)->validate();


			$supplierOrder = SupplierOrder::create([
				'supplier_id'=>$request->get('supplier_id'),
				'user_id' => Auth::id()
			]);

			for($i = 0; $i<count(request('name'));$i++)
			{
				$this->addSupplierOrder($request->get('name')[$i],
					$request->get('requested_units')[$i],
					$supplierOrder);
			}

		}else{
			$request->validate([
				'requested_units' => 'required'
			]);
			$this->addSupplierOrder($request->get('name'),$request->get('requested_units'));
		}


	    return back()->with('success',trans('global.po_is_requested',['qty'=>'','name'=>'']));

    }


    private function addSupplierOrder($product_id, $requested_units, $supplierOrder = null){

	    $product = Product::find($product_id);

		if(!$supplierOrder){
			$supplierOrder = SupplierOrder::create([
				'supplier_id'=>$product->supplier_id,
				'user_id' => Auth::id()
			]);
		}

	    SupplierOrderItems::create([
		    'supplier_order_id'=>$supplierOrder->id,
		    'product_id'=>$product->id,
		    'requested_units'=>$requested_units
	    ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SupplierOrder  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierOrder $supplierOrder)
    {
        //
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }
	    $roles = Role::get()->pluck('name', 'name');

	    return view('admin.supplier_order.show', compact('roles','supplierOrder'));


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SupplierOrder  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SupplierOrder $supplierOrder, Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SupplierOrder  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierOrder $supplierOrder)
    {

	    $supplierOrder->update($request->all());
		//:todo add to stock

	    if($request->get('received_units')){
		    $product = Product::find($supplierOrder->product_id);
		    $product->stock += $request->get('received_units') ;
		    $product->save();
	    }

	    return back()->with('success',trans('global.is_updated'));

    }

    public function grn(supplierOrder $supplierOrder)
    {
	    return view('admin.supplier_order.grn', compact('supplierOrder'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SupplierOrder  $supplierOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplierOrder $supplierOrder)
    {
	    $supplierOrder->status = SupplierOrder::cancel;
	    $supplierOrder->save();
	    return back()->with('alert',trans('global.po_is_cancelled',['name'=>$supplierOrder->uid]));
    }


}
