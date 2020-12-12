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
                    <label>Status&nbsp;</label>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button name="status" value="requested" type="submit"
                                class="btn btn-default {{(request('status')=='requested')?'active':''}} {{request('status')??'active'}}">
                            Requested
                        </button>
                        <button name="status" value="received" type="submit"
                                class="btn btn-default {{(request('status')=='received')?'active':''}}">Received
                        </button>
                        <button name="status" value="cancelled" type="submit"
                                class="btn btn-default {{(request('status')=='cancelled')?'active':''}}">Cancelled
                        </button>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="search" name="search" placeholder="Supplier Order ID" class="form-control"
                               value="{{old('search',request('search'))}}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($supplierOrders->count())
                @foreach($supplierOrders as $key => $supplierOrder)
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="row">
                                <div class="col-3">
                                    <strong>ID :</strong> {{$supplierOrder->uid}}
                                </div>
                                <div class="col-3">
                                    <strong>Supplier :</strong> {{$supplierOrder->supplier->name}}
                                </div>
                                <div class="col-3">
                                    @if($supplierOrder->status != \App\SupplierOrder::complete)
                                        <strong>Requested Date :</strong>  {{$supplierOrder->created_at}}
                                    @endif
                                    @if($supplierOrder->status == \App\SupplierOrder::complete)
                                        <strong>Received Date :</strong>  {{$supplierOrder->received_date??'--'}}
                                    @endif
                                </div>
                                <div class="col-3">
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.supplier_order.show', $supplierOrder->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                    @cannot('sales_executive')
                                        @if($supplierOrder->status==\App\SupplierOrder::pending)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ route('admin.supplier_order.grn', $supplierOrder) }}">
                                                {{trans('global.grn')}}
                                            </a>
                                        @endif
                                        @if($supplierOrder->status==\App\SupplierOrder::pending)
                                            <form metdod="post" class=""
                                                  action="{{ route('admin.supplier_order.destroy', $supplierOrder->id) }}"
                                                  onsubmit="confirmSubmit(this);return false;"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-xs btn-danger">{{ trans('global.cancel') }}</button>
                                            </form>
                                        @endif
                                    @endcannot
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="m-0 p-0 table table-borderless table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Requested Units</th>
                                    <th class="text-center">Received Units</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($supplierOrder->supplierOrderItems as $item)
                                    <tr>
                                        <td>{{$item->product->name}}</td>
                                        <td class="text-center">{{$item->requested_units}}</td>
                                        <td class="text-center">{{$item->received_units??'--'}}</td>
                                        <td class="text-right">{{$item->unit_price??'--'}}</td>
                                        <td class="text-right">{{$item->total_price??'--'}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-right"></td>
                                    <td class="text-right"><strong>{{trans('global.format_price',['price'=>$supplierOrder->total_amount])}}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            {{$supplierOrders->links()}}
            @else
             <empty-results message="{{trans('global.no_entries_in_table')}}"></empty-results> 
             @endif
        </div>
    </div>
@endsection

@section('nav-item')
    @component('partials.navitem')
        @slot('links')
            @can('users_manage')
                <a class="nav-item nav-link" href="{{route('admin.supplier_order.create')}}"><i class="fa fa-plus"></i>
                    Add Supplier Order</a>
            @endcan
        @endslot
    @endcomponent
@endsection