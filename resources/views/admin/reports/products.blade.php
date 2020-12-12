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
                    <label for="code">Code</label>
                    <input name="code" class="form-control" type="text" value="{{request()->input('code')}}">
                </div>
                <div class="col-md-4">
                    <label for="name">Product Name</label>
                    <input name="name" class="form-control" type="text" value="{{request()->input('name')}}">
                </div>
                <div class="col-md-4">
                    <label for="supplier_name">Supplier</label>
                    <input name="supplier_name" class="form-control" type="text" value="{{request()->input('supplier_name')}}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="btn-group mt-4" data-toggle="buttons">
                        <label class="btn btn-default {{(request()->get('sort')=='sales,desc')?'active':''}}">
                            <input type="radio" name="sort" id="option1" value="sales,desc" {{(request()->get('sort')=='sales,desc')?'checked':''}} > Sales High to Low
                        </label>
                        <label class="btn btn-default {{(request('sort')=='sales,asc')?'active':''}}">
                            <input type="radio" name="sort" id="option2" value="sales,asc" {{(request('sort')=='sales,asc')?'checked':''}}>Sales Low to High
                        </label>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4"><strong></strong></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="btn-group pull-right">
                    <a href="{{route('admin.reports.products')}}" class="btn btn-default mt-2">Reset</a>
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
            <th>Code</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Supplier</th>
            @if(request('sort'))
                <th>Sales Qty</th>
            @endif
            </thead>
            <tbody>
        @foreach($products as $product)
            <tr>
                <td><a href="{{route('admin.products.show',$product->id)}}">{{$product->code}}</a></td>
                <td>{{$product->name}}</td>
                <td>{{$product->brand}}</td>
                <td>{{$product->supplier_name}}</td>
                @if(request('sort'))
                <td>{{$product->qty??''}}</td>
                @endif
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