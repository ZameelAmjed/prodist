<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupplierStoreRequest;
use App\Location;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class SuppliersController extends Controller
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


	    if($request->get('name',false)){
		    $suppliers = Supplier::where('name', 'like', "%{$request->get('name')}%")->paginate(20);
		    if(!count($suppliers)){
			    $suppliers = Supplier::paginate(20);
			    return view('admin.suppliers.index', compact('suppliers'))
				    ->withErrors(['name' => ["{$request->get('name')} Not Found"]]);
		    }
	    }else{
		    $suppliers = Supplier::paginate(20);
	    }

	    return view('admin.suppliers.index', compact('suppliers'));

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

	    return view('admin.suppliers.create', compact('roles'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierStoreRequest $request)
    {
	    $supplier = Supplier::create($request->all());

	    return redirect()->route('admin.suppliers.index')
	                     ->with('success',"$supplier->name ".trans('global.is_created'));


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function update(Request $request,Supplier $supplier)
	{
		if (! Gate::allows('users_manage')) {
			return abort(401);
		}

		$supplier->update($request->all());

		return back()->with('message',"$supplier->name ". trans('global.is_updated'));

	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function destroy(Supplier $supplier)
	{
		//If nothing under this is available delete
		$supplier->delete();

		return redirect()->route('admin.suppliers.index')->with('alert',"$supplier->name ".trans('global.is_deleted'));

	}
}
