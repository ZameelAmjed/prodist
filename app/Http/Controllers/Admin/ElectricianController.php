<?php

namespace App\Http\Controllers\Admin;

use App\Dealers;
use App\Electrician;
use App\Http\Controllers\Exports\EleImp;
use App\Http\Requests\Admin\StoreElectricianRequest;
use App\Http\Requests\Admin\UpdateElectricianRequest;
use App\Setuprepo;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Image;
use Maatwebsite\Excel\Facades\Excel;

class ElectricianController extends Controller
{

    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $electricians = Electrician::paginate(15);

	    if($request->get('nic',false)){
		    $electrician = Electrician::where('nic', '=', $request->get('nic'))->Orwhere('telephone', '=', $request->get('nic'))->Orwhere('member_code', '=', $request->get('nic'))
		                              ->first();
		    if(isset($electrician)){
			    return redirect()->route('admin.electrician.show', $electrician);
		    }else{
			    return view('admin.electrician.index', compact('electricians'))->withErrors(['nic' => ['Electrician Not Found']]);
		    }
	    }

        return view('admin.electrician.index', compact('electricians'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*if (! Gate::allows('users_manage')) {
            return abort(401);
        }*/

		$dealers = Dealers::all('id','name')->toJson();

        return view('admin.electrician.create', ['dealers'=>$dealers]);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreElectricianRequest $request)
    {
        /*if (! Gate::allows('users_manage')) {
            return abort(401);
        }*/
		//print_r($request->all());die();

        $electrician = Electrician::create($request->all());

        if(isset($electrician) && $request->file('photo'))
        {
	        $imageName = time().$electrician->id.'.jpg';
	        Image::make($request->file('photo'))->save('images/electrician/'.$imageName,80,'jpg');
	        $electrician->photo = $imageName;
	        $electrician->save();
        }

        return redirect()->route('admin.electrician.show',$electrician)->with('message',"$electrician->name ".trans('global.is_created'));
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Electrician $electrician)
    {
        if (! Gate::denies('sales_executive')) {
            return abort(401);
        }

        return view('admin.electrician.edit', compact('electrician'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateElectricianRequest $request, Electrician $electrician)
    {
        /*if (! Gate::allows('users_manage')) {
            return abort(401);
        }*/
	    if($request->has('status')){
		    $electrician->status = $request->input('status');
		    $electrician->approved_by = Auth::id();
		    $electrician->save();
	    }else{
		    $electrician->update($request->all());
		    //image is set delete old add new
		    if($request->file('photo')){
			    if(File::exists('images/electrician/'.$electrician->photo)) {
				    File::delete('images/electrician/'.$electrician->photo);
			    }

			    $electrician->photo = time().$electrician->id.'.jpg';
			    $electrician->save();
			    Image::make($request->file('photo'))->save('images/electrician/'.$electrician->photo,80,'jpg');
		    }

	    }


        return back()->with('message',"$electrician->name ".trans('global.is_updated'));
    }

    public function show(Electrician $electrician)
    {
        /*if (! Gate::allows('users_manage')) {
            return abort(401);
        }*/

        return view('admin.electrician.show', compact('electrician'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('alert',"$user->name ".trans('global.is_deleted'));
    }



    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        User::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

    public function converter(Request $request){
    	echo "HI";
	    Excel::import(new EleImp,public_path('100ele1.xlsx'));
    }


}
