@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Purchase Orders','url'=>route('admin.purchase_orders.index')],
['name'=>'View','url'=>route('admin.purchase_orders.show',$purchaseOrder)],
],
'pageimage'=>'box.svg'])
@section('content')
@component('partials.backbutton')
@slot('more')
    @can('users_manage')
        @if($purchaseOrder->status == \App\PurchaseOrder::pending)
            <a class="btn btn-success" href="{{route('admin.purchase_orders.grn',$purchaseOrder->id)}}">
                {{ trans('global.grn') }}
            </a>
        @endif
            {{ Form::open(array('url' => route('admin.purchase_orders.destroy', [$purchaseOrder->id]),'class'=>'form-inline-block')) }}
            @method('DELETE')
            {{ Form::hidden('status',\App\PurchaseOrder::cancel) }}
            {{ Form::submit('Cancel',['class'=>'btn btn-danger']) }}
            {{ Form::close() }}
    @endcan
@endslot
@endcomponent
<div class="card">
    <div class="card-header">
        {{trans('cruds.purchase_order.title_singular')}}<strong></strong></div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{trans('global.id')}}
                    </th>
                    <td>
                        {{ $purchaseOrder->uid }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{trans('cruds.purchase_order.fields.supplier_ref_code')}}
                    </th>
                    <td>
                        {{ $purchaseOrder->supplier_ref_code }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Batch No
                    </th>
                    <td>
                        {{ $purchaseOrder->batch_no??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Invoice No
                    </th>
                    <td>
                        {{ $purchaseOrder->invoice_no??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Received Date
                    </th>
                    <td>
                        {{ $purchaseOrder->received_date??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Total Invoice Amount
                    </th>
                    <td>
                        {{ number_format($purchaseOrder->total_amount,2)??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                            {!! trans('global.'.$purchaseOrder->status) !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.purchase_order.fields.created_at') }}
                    </th>
                    <td>
                        {{$purchaseOrder->created_at}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.updated_at') }}
                    </th>
                    <td>
                        {{$purchaseOrder->updated_at}}
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="panel panel-default p-1">
                <div class="panel-title">
                    <h5>Items</h5>
                </div>
                <div class="panel-body">
                    <table class="m-0 table table-borderless table-condensed">
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Expiry Date</th>
                            <th class="text-center">Requested Units</th>
                            <th class="text-center">Received Units</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total Price</th>
                        </tr>
                        @foreach($purchaseOrder->purchaseOrderItems as $item)
                            <tr>
                                <td>{{$item->product->name}}</td>
                                <td class="text-center">{{$item->expiry_date??'--'}}</td>
                                <td class="text-center">{{$item->requested_units}}</td>
                                <td class="text-center">{{$item->received_units}}</td>
                                <td class="text-right">@currency($item->unit_price)</td>
                                <td class="text-right">@currency($item->total_price)</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection