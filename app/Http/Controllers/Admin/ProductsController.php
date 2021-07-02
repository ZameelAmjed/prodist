<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Exports\BarcodeExport;
use App\Http\Controllers\Traits\ProductTrait;
use App\Http\Requests\Admin\StoreProductsRequest;
use App\Http\Requests\Admin\UpdateProductsRequest;
use App\Product;
use App\PurchaseOrder;
use App\Supplier;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use PDF;
use DNS1D;
use DNS2D;
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
		        ->orWhere('brand','like','%'.request('search').'%')
		        ->orWhere('code','=',request('search'));
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

		//if files available
		$this->moveImageFromTemp($product, $request->get('files',null));

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

       $pendingPurchaseOrderIds = DB::table('purchase_orders')
                                  ->join('purchase_order_items',function($join) use ($product){
         $join->on('purchase_orders.id','=','purchase_order_items.purchase_order_id')
	         ->where('purchase_order_items.product_id','=',$product->id);

       })->where('purchase_orders.status','=',PurchaseOrder::pending)
         ->get()->pluck('id');
	    $pendingPurchaseOrders = PurchaseOrder::find($pendingPurchaseOrderIds);

	    $completedPurchaseOrderIds = DB::table('purchase_orders')
	                                 ->join('purchase_order_items',function($join) use ($product){
		                                 $join->on('purchase_orders.id','=','purchase_order_items.purchase_order_id')
		                                      ->where('purchase_order_items.product_id','=',$product->id);

	                                 })->where('purchase_orders.status','=',PurchaseOrder::complete)
	                                 ->get()->pluck('id');
	    $completedPurchaseOrders = PurchaseOrder::find($completedPurchaseOrderIds);

	    $secret = md5(uniqid());
	    request()->session()->flash('secret', $secret);

	    return view('admin.products.show', compact('product','pendingPurchaseOrders','completedPurchaseOrders','secret'));
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

	/**
	 * @param Request $request
	 */
    public function find(Request $request){
    	$products = Product::where('code','like','%'.$request->get('keyword').'%')->get();
    	return response()->json(['data'=>$products->toJson()]);
    }


	/**
	 * @param $files
	 * @param Product $product
	 */
    public function moveImageFromTemp(Product $product, $files){
	    if($files){
		    //try catch
		    try{
			    foreach ($files as $file){
				    $path = public_path('product/'.$product->id);
				    File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
				    File::move(public_path('tempimage'.'/'.$file), $path.'/'.$file);
			    }
		    }catch (Exception $ex){

		    }

	    }
    }



}
