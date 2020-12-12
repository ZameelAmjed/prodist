<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Store;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreStoresRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class StoresController extends Controller
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

        $storesQuery = Store::select();

        if(request('search',false)){

            $storesQuery = $storesQuery->where('business_name', 'like', '%'.request('search').'%')->orWhere('owner_name', 'like', '%'.request('search').'%');
        }

        $stores = $storesQuery->paginate(15);

	    return view('admin.stores.index', compact('stores'));

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

	    return view('admin.stores.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoresRequest $request)
    {
        $store = Store::create($request->all());

	    return redirect()->route('admin.stores.index')
	                     ->with('success',"$store->business_name ".trans('global.is_created'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    return view('admin.stores.show', compact('store'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    return view('admin.stores.edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Store $store)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    $store->update($request->all());

	    return back()->with('message',"$store->business_name ". trans('global.is_updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
	    //If nothing under this is available delete
	    $store->delete();

	    return redirect()->route('admin.stores.index')->with('alert',"$store->business_name ".trans('global.is_deleted'));

    }
}
