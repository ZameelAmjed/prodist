<?php

namespace App\Http\Controllers\Admin;

use App\Dealers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Exports\ElectricianExport;
use App\Http\Controllers\Exports\ProductsExport;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ReportsController extends Controller
{
    public function getProducts(Request $request)
    {

	    $query = DB::connection('mongodb')->collection('products');

	    if($request->input('from') && $request->input('to'))
	    {
		    $request->validate(
			    [
				    'from' => 'date',
				    'to' => 'date',
			    ]
		    );
		    $query->whereBetween('created_at', [$request->input('from'), $request->input('from')]);
	    }

	    if($request->get('model'))
	    {
		    $query->where('model', '=', $request->get('model'));
	    }

	    if($request->get('product_name'))
	    {
		    $query->where('product_name', 'like', "%{$request->input('product_name')}%");
	    }

	    if($request->get('export'))
	    {
		    //download excel
		    return Excel::download(new ProductsExport($query), 'Products-'.date('Y-m-d').'.xlsx');
	    }

	    $products = $query->paginate(100);

	    return view('admin.reports.products', compact('products'));
    }

	public function getElectrician(Request $request)
	{

		$query = DB::table('electricians');

		if($request->input('nic'))
		{
			$query->where('nic', '=',$request->input('nic'))->orWhere('telephone','=', $request->input('nic'));
		}

		if($request->input('city'))
		{
			$query->where('city', '=',$request->input('city'));
		}

		if($request->input('province'))
		{
			$query->where('province', '=',$request->input('province'));
		}

		if($request->input('celebration'))
		{
			$query->where('celebration', '=',$request->input('celebration'));
		}

		if($request->input('dealer'))
		{
			$dealer = Dealers::where('name',$request->input('dealer'))->get()->first();
			if($dealer)
			$query->where('dealer_id', '=',$dealer->id);
		}

		if($request->input('points'))
		{
			if($request->input('points')=='fasc' || $request->input('points')=='fdesc')
				$query->orderBy('float_points',ltrim($request->input('points'),'f'));
			else
				$query->orderBy('points',$request->input('points'));
		}



		if($request->get('export'))
		{
			//download excel
			return Excel::download(new ElectricianExport($query), 'Electricians-'.date('Y-m-d').'.xlsx');
		}

		$electricians = $query->paginate(100);

		return view('admin.reports.electricians', compact('electricians'));
	}

	public function getDealers(Request $request)
	{

		$query = DB::table('dealers');

		if($request->input('business_name'))
		{
			$query->where('business_name', 'like',"%{$request->input('business_name')}%");
		}

		if($request->input('name'))
		{
			$query->where('name', 'like',"%{$request->input('name')}%");
		}

		if($request->input('city'))
		{
			$query->where('city', '=',$request->input('city'));
		}

		if($request->input('area'))
		{
			$query->where('area', '=',$request->input('area'));
		}

		if($request->input('region'))
		{
			$query->where('region', '=',$request->input('region'));
		}



		if($request->get('export'))
		{
			//download excel
			return Excel::download(new ElectricianExport($query), 'Electricians-'.date('Y-m-d').'.xlsx');
		}

		$dealers = $query->paginate(100);

		return view('admin.reports.dealers', compact('dealers'));
	}
}
