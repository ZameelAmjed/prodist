<?php

namespace App\Http\Controllers\Admin;

use App\Dealers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Psy\Util\Json;

class DealersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $dealers = Dealers::paginate(15);

	    if($request->get('name',false)){
		    $dealer = Dealers::where('name', '=', $request->get('name'))->first();
		    if(isset($dealer)){
			    return redirect()->route('admin.dealers.show', $dealer);
		    }
	    }

	    return view('admin.dealer.index', compact('dealers'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
	    return view('admin.dealer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    $dealer = Dealers::create($request->all());

	    return redirect()->route('admin.dealers.show', $dealer)->with('message',"$dealer->business_name ".trans('global.is_created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dealers $dealer)
    {
	    /*if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }*/

	    return view('admin.dealer.show', compact('dealer'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Dealers $dealer)
    {
	    if (! Gate::allows('users_manage')) {
		    return abort(401);
	    }

	    return view('admin.dealer.edit', compact('dealer'));
	    //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dealers $dealer)
    {
	    $dealer->update($request->all());

	    return back()->with('message',"$dealer->business_name ". trans('global.is_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$dealer = Dealers::find($id);
	    Dealers::destroy($id);

	    return back()->with('alert',"$dealer->business_name ".trans('global.is_deleted'));
    }
}
