<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Exports\BarcodeExport;
use App\Http\Controllers\Traits\ProductTrait;
use App\Http\Requests\Admin\StoreProductsRequest;
use App\Http\Requests\Admin\UpdateProductsRequest;
use App\Product;
use App\User;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use PDF;
use DNS1D;
use DNS2D;
use Maatwebsite\Excel\Concerns\FromCollection;
class ProductsController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    use ProductTrait;

    public function index(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $products = Product::paginate(20);
	    if($request->get('model',false)){
		    $product = Product::where('model', '=', $request->get('model'))->first();
		    if(isset($product)){
			    return redirect()->route('admin.products.show', $product);
		    }else{
			    return view('admin.products.index', compact('products'))->withErrors(['model' => ['Product Not Found']]);
		    }
	    }
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        return view('admin.products.create', compact('roles'));
    }

	public function store(StoreProductsRequest $request)
	{
		if (! Gate::allows('users_manage')) {
			return abort(401);
		}

		$product = Product::create($request->all());

		return redirect()->route('admin.products.show',[$product->id])->with('message',"$product->product_name ".trans('global.is_created'));
	}


    public function addUnits(Product $product, Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

	    $validator = Validator::make($request->all(), [
		    'no_of_units' => 'required|int|min:1',
	    ]);

	    if ($validator->fails()) {
		    return redirect()->route('admin.products.show', $product)
			    ->withErrors($validator)
			    ->withInput();
	    }else{
		    $product->units_issued = $product->units_issued + $request->input('no_of_units');
	    	$product->save();
	    }

        return redirect()->route('admin.products.show', $product);
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update Product in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductsRequest $request, Product $product)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $product->update($request->all());

        return back()->with('message',"$product->product_name ". trans('global.is_updated'));
    }

    public function show(Product $product)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

	    return view('admin.products.show', compact('product'));
    }

    /**
     * Remove Productfrom storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
    	//If nothing under this is available delete
        $product->delete();

        return redirect()->route('admin.products.index')->with('alert',"$product->product_name ".trans('global.is_deleted'));
    }

    /**
     * Delete all selected Product at once.
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

	public function printCode(Product $product, Request $request)
	{
		if (! Gate::allows('users_manage')) {
			return abort(401);
		}

		$request->validate([
			'start' => 'numeric|min:1',
			'end' => 'numeric|gt:start',
		]);

		if(($request->input('end')-$request->input('start')) >= $product->units_issued){
			return back()->withErrors(['end' => ["Only $product->units_issued product units issued"]]);
		}


		$attr = $request->all();

		if($attr['type']==='pdf'){
			if(($request->input('end')-$request->input('start')) >= 500){
				//error exceeding 500 range
				return back()->withErrors(['end' => ['Maximum range allowed is 500']]);
			}
			//return view('admin.products.pdf', compact('product','attr'));
			$pdf =  PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('admin.products.pdfe', compact('product','attr'))->setPaper('a4', 'portrait');
			return $pdf->download($product->product_name.'-'.$product->model.'-'.$request->input('start').'-'.$request->input('end').'.pdf');
		}else{
			return Excel::download(new BarcodeExport($product, $request->input('start'), $request->input('end')), $product->product_name.'-'.$product->model.'-'.$request->input('start').'-'.$request->input('end').'.xlsx');
		}

	}



}
