@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Supplier Order','url'=>route('admin.supplier_order.index')],
],
'pageimage'=>'inventory.svg'])
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.supplier_order.title_singular') }} {{ trans('global.list') }}
        <div class="pull-right">
            <form method="get" class="form-inline">
                <label>Status: </label>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button name="status" value="requested" type="submit" class="btn btn-secondary {{(request('status')=='requested')?'active':''}} {{request('status')??'active'}}">Requested</button>
                    <button name="status" value="received" type="submit" class="btn btn-secondary {{(request('status')=='received')?'active':''}}">Received</button>
                    <button name="status" value="cancelled" type="submit" class="btn btn-secondary {{(request('status')=='cancelled')?'active':''}}">Cancelled</button>
                </div>
                <div class="col-md-4">
                    <input type="text" id="search" name="search" placeholder="Search ID" class="form-control" value="{{old('search',request('search'))}}">
                </div>
                </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
                    @foreach($supplierOrders as $key => $supplierOrder)
                <table class="table table-bordered table-hover mt-5">
                    <thead>
                    <tr>
                        <td>
                            <strong>ID :</strong> {{$supplierOrder->uid}}
                        </td>
                        <td>
                            <strong>Supplier :</strong> {{$supplierOrder->supplier->name}}
                        </td>
                        <td>
                            <strong>Requested Date :</strong>  {{$supplierOrder->created_at}}
                        </td>
                        <td>
                            <strong>Received Date :</strong>  {{$supplierOrder->received_date??'--'}}
                        </td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.supplier_order.show', $supplierOrder->id) }}">
                                {{ trans('global.view') }}
                            </a>
                            @cannot('sales_executive')
                                @if($supplierOrder->status==\App\SupplierOrder::pending)
                                    <a class="btn btn-xs btn-success" href="{{ route('admin.supplier_order.grn', $supplierOrder) }}">
                                        {{trans('global.grn')}}
                                    </a>
                                @endif
                                @if($supplierOrder->status==\App\SupplierOrder::pending)
                                    <form metdod="post" class="" action="{{ route('admin.supplier_order.destroy', $supplierOrder->id) }}" onsubmit="confirmSubmit(this);return false;" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">{{ trans('global.cancel') }}</button>
                                    </form>
                                @endif
                            @endcannot
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">
                                <table class="m-0 table table-borderless table-condensed">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Requested Units</th>
                                        <th class="text-center">Received Units</th>
                                        <th class="text-left">Unit Price</th>
                                        <th class="text-left">Total Price</th>
                                    </tr>
                                @foreach($supplierOrder->supplierOrderItems as $item)
                                   <tr>
                                        <td>{{$item->product->name}}</td>
                                        <td class="text-center">{{$item->requested_units}}</td>
                                        <td class="text-center">{{$item->received_units??'--'}}</td>
                                        <td class="text-right">{{$item->unit_price??'--'}}</td>
                                        <td class="text-right">{{$item->total_price??'--'}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    @endforeach
        </div>
{{$supplierOrders->links()}}

    </div>
</div>
@endsection

@section('nav-item')
    @component('partials.navitem')
        @slot('links')
            @can('users_manage')
                <a class="nav-item nav-link" href="{{route('admin.supplier_order.create')}}"><i class="fa fa-plus"></i> Add Supplier Order</a>
            @endcan
            @endslot
    @endcomponent
@endsection