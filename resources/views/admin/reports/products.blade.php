@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Reports','url'=>route('admin.reports.products')],
],
'pageimage'=>'report.svg'])
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.products.title_singular') }} {{ trans('global.reports') }}
        </div>
        <div class="card-body">
            <form method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="model">Model</label>
                    <input name="model" class="form-control" type="text" value="{{request()->input('model')}}">
                </div>
                <div class="col-md-4">
                    <label for="product_name">Product Name</label>
                    <input name="product_name" class="form-control" type="text" value="{{request()->input('product_name')}}">
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="from">Created Date From</label>
                    <input name="from" class="form-control" type="date" value="{{request()->input('from')}}">
                </div>
                <div class="col-md-4">
                    <label for="to">Created Date To</label>
                    <input name="to" class="form-control date" type="date" value="{{request()->input('to')}}">
                </div>
                <div class="col-md-4"><strong></strong></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                    <button name="export" value="excel" type="submit" class="btn btn-warning text-white mt-2">Export</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="card">
        @if(count($products))
        <table class="table table-condensed">
            <thead>
            <th>Product Name</th>
            <th>Model</th>
            <th>Description</th>
            <th>Textcode</th>
            <th>Points</th>
            <th>Units Issued</th>
            <th>Units Active</th>
            <th>Last Barcode</th>
            </thead>
            <tbody>
        @foreach($products as $product)
            <tr>
                <td><a href="{{route('admin.products.show',$product['_id'])}}">{{$product['product_name']}}</a></td>
                <td>{{$product['model']}}</td>
                <td>{{$product['description']}}</td>
                <td>{{$product['textcode']}}</td>
                <td>{{$product['points']}}</td>
                <td>{{isset($product['units_issued'])?$product['units_issued']:0}}</td>
                <td>{{isset($product['units_active'])?$product['units_active']:0}}</td>
                <td>{{isset($product['last_barcode'])?$product['last_barcode']:'-'}}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
        <div class="justify-content-center mt-5">
            {{$products->links()}}
        </div>

        @else
            <div class="alert alert-info mb-0">{{trans('global.search_nothing_found')}}</div>
        @endif
    </div>
@endsection