<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Exports\BarcodeExport;
use App\Http\Controllers\Traits\ProductTrait;
use App\Http\Requests\Admin\StoreProductsRequest;
use App\Http\Requests\Admin\UpdateProductsRequest;
use App\Product;
use App\Supplier;
use App\SupplierOrder;
use App\User;

use Illuminate\Support\Facades\Auth;
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

        $productsQuery = Product::select();

        if(request('search') && request('supplier')){
	        $productsQuery = $productsQuery
		        ->join('suppliers','products.supplier_id','suppliers.id')
		        ->select('products.*')
		        ->where(function($q){
			        $q->where('suppliers.name','like','%'.request('search').'%');
		        });
        }elseif (request('search')){
	        $productsQuery = $productsQuery
		        ->where('name','like','%'.request('search').'%')
		        ->orWhere('brand','like','%'.request('search').'%');
        }

		$products = $productsQuery->paginate(15);

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

        $suppliers = Supplier::all();

        return view('admin.products.create', compact('roles','suppliers'));
    }

	public function store(StoreProductsRequest $request)
	{
		if (! Gate::allows('users_manage')) {
			return abort(401);
		}

		$product = Product::create($request->all());

		return redirect()->route('admin.products.show',[$product->id])->with('message',"$product->name ".trans('global.is_created'));
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
	    $suppliers = Supplier::all();
        return view('admin.products.edit', compact('product','suppliers'));
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

        return back()->with('message',"$product->name ". trans('global.is_updated'));
    }

    public function show(Product $product)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

	    $pendingSupplierOrders = SupplierOrder::where('status',\App\SupplierOrder::pending)->get();

	    $completedSupplierOrders = SupplierOrder::where('status',\App\SupplierOrder::complete)->get();

	    return view('admin.products.show', compact('product','pendingSupplierOrders','completedSupplierOrders'));
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

        return redirect()->route('admin.products.index')->with('alert',"$product->name ".trans('global.is_deleted'));
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



}
