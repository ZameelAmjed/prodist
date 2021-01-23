@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Purchase Orders','url'=>route('admin.purchase_orders.index')],
],
'pageimage'=>'inventory.svg'])
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.purchase_order.title_singular') }} {{ trans('global.list') }}
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
                        <input type="text" id="search" name="search" placeholder="Purchase Order ID" class="form-control"
                               value="{{old('search',request('search'))}}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($purchaseOrders->count())
                @foreach($purchaseOrders as $key => $purchaseOrder)
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="row">
                                <div class="col-3">
                                    <strong>ID :</strong> {{$purchaseOrder->uid}}
                                </div>
                                <div class="col-3">
                                    <strong>Supplier :</strong> {{$purchaseOrder->supplier->name}}
                                </div>
                                <div class="col-3">
                                    @if($purchaseOrder->status != \App\PurchaseOrder::complete)
                                        <strong>Requested Date :</strong>  {{$purchaseOrder->created_at}}
                                    @endif
                                    @if($purchaseOrder->status == \App\PurchaseOrder::complete)
                                        <strong>Received Date :</strong>  {{$purchaseOrder->received_date??'--'}}
                                    @endif
                                </div>
                                <div class="col-3">
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.purchase_orders.show', $purchaseOrder->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                    @cannot('sales_executive')
                                        @if($purchaseOrder->status==\App\PurchaseOrder::pending)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ route('admin.purchase_orders.grn', $purchaseOrder) }}">
                                                {{trans('global.grn')}}
                                            </a>
                                        @endif
                                        @if($purchaseOrder->status==\App\PurchaseOrder::pending)
                                            <form metdod="post" class=""
                                                  action="{{ route('admin.purchase_orders.destroy', $purchaseOrder->id) }}"
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
                                @foreach($purchaseOrder->purchaseOrderItems as $item)
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
                                    <td class="text-right"><strong>{{trans('global.format_price',['price'=>$purchaseOrder->total_amount])}}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            {{$purchaseOrders->links()}}
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
                <a class="nav-item nav-link" href="{{route('admin.purchase_orders.create')}}"><i class="fa fa-plus"></i>
                    Add Purchase Order</a>
            @endcan
        @endslot
    @endcomponent
@endsection