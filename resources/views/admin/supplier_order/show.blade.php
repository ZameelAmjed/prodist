@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Supplier Order','url'=>route('admin.supplier_order.index')],
['name'=>'View','url'=>route('admin.supplier_order.show',$supplierOrder)],
],
'pageimage'=>'box.svg'])
@section('content')
@component('partials.backbutton')
@slot('more')
    @can('users_manage')
            <a class="btn btn-success" href="#">
                {{ trans('global.grn') }}
            </a>
            {{ Form::open(array('url' => route('admin.supplier_order.destroy', [$supplierOrder->id]),'class'=>'form-inline-block')) }}
            @method('DELETE')
            {{ Form::hidden('status',\App\SupplierOrder::cancel) }}
            {{ Form::submit('Cancel',['class'=>'btn btn-danger']) }}
            {{ Form::close() }}
    @endcan
@endslot
@endcomponent
<div class="card">
    <div class="card-header">
        {{trans('cruds.supplier_order.title_singular')}}<strong></strong></div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{trans('global.id')}}
                    </th>
                    <td>
                        {{ $supplierOrder->uid }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{trans('cruds.supplier_order.fields.supplier_ref_code')}}
                    </th>
                    <td>
                        {{ $supplierOrder->supplier_ref_code }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Batch No
                    </th>
                    <td>
                        {{ $supplierOrder->batch_no??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Invoice No
                    </th>
                    <td>
                        {{ $supplierOrder->invoice_no??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Received Date
                    </th>
                    <td>
                        {{ $supplierOrder->received_date??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Total Invoice Amount
                    </th>
                    <td>
                        {{ $supplierOrder->total_amount??'--' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                            {!! trans('global.'.$supplierOrder->status) !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.supplier_order.fields.created_at') }}
                    </th>
                    <td>
                        {{$supplierOrder->created_at}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.updated_at') }}
                    </th>
                    <td>
                        {{$supplierOrder->updated_at}}
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
                        @foreach($supplierOrder->supplierOrderItems as $item)
                            <tr>
                                <td>{{$item->product->name}}</td>
                                <td class="text-center">{{$item->expiry_date??'--'}}</td>
                                <td class="text-center">{{$item->requested_units}}</td>
                                <td class="text-center">{{$item->received_units}}</td>
                                <td class="text-right">{{$item->unit_price}}</td>
                                <td class="text-right">{{$item->total_price}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection