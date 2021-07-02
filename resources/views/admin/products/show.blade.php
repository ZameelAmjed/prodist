@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Products','url'=>route('admin.products.index')],
['name'=>'View','url'=>route('admin.products.show',$product)],
],
'pageimage'=>'box.svg'])
@section('content')
@component('partials.backbutton')
@slot('more')
<a class="btn btn-info" href="{{ route('admin.products.edit', $product->id) }}">
    {{ trans('global.edit') }}
</a>
@endslot
@endcomponent
<div class="card">
    <div class="card-header">
        {{trans('cruds.products.title_singular')}} - <strong>{{$product->name}}</strong></div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.name') }}
                    </th>
                    <td>
                         {{$product->name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.brand') }}
                    </th>
                    <td>
                       {{$product->brand}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.code') }}
                    </th>
                    <td>
                        {{$product->code}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.retail_price') }}
                    </th>
                    <td>
                        @currency($product->retail_price)
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.distributor_price') }}
                    </th>
                    <td>
                        @currency($product->distributor_price)
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.supplier') }}
                    </th>
                    <td>
                        {{$product->supplier->name??''}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.stock') }}
                    </th>
                    <td>
                        {{$product->stock??0}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.created_at') }}
                    </th>
                    <td>
                        {{$product->created_at}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.updated_at') }}
                    </th>
                    <td>
                        {{$product->updated_at}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        {{--Images--}}
        <div class="panel panel-default p-1">
            <div class="panel-title">
                <h5>Product Images</h5>
            </div>
            <div class="panel-body">
                @if($images = App\Product::getImageList($product->id))
                    @foreach($images as $image)
                        <img src="{{ asset($image) }}" class="img img-thumbnail d-inline"/>
                    @endforeach
                @else
                    <span>No images available</span>
                @endif
            </div>
        </div>
        <div class="panel panel-default p-1">
            <div class="panel-title">
                <h5>{{trans('global.request_items')}}</h5>
            </div>
            <div class="panel-body">
                {{ Form::open(array('url' => route('admin.purchase_orders.store'),'class'=>'form-inline')) }}
                @csrf
                <div class="form-group">
                    <label>{{trans('global.qty')}}
                        {{Form::number('requested_units',null,['step'=>'1','class'=>'form-control mr-2'])}}
                        {{Form::hidden('product_id',$product->id)}}
                    </label>
                </div>
                {{Form::submit('submit',['class'=>'btn btn-primary'])}}
                {{ Form::close() }}
                <div class="help-block">{{trans('global.po_product_form_help_text')}}</div>
                @if($pendingPurchaseOrders->count())
                <table class="table table-condensed mt-5">
                    <tr>
                        <th>Purchase Order ID</th>
                        <th>Units</th>
                        <th>Requested Date</th>
                        <th>&nbsp;</th>
                    </tr>
                    @foreach($pendingPurchaseOrders as $pso)
                        @foreach($pso->purchaseOrderItems as $item)
                        <tr>
                            <td>{{$pso->uid}}</td>
                            <td>{{$item->requested_units}}</td>
                            <td>{{$pso->created_at}}</td>
                            <td>
                                <a class="btn btn-sm btn-success list-inline-item" href="{{route('admin.purchase_orders.grn',$pso)}}">GRN</a>
                                {{--Quick GRN--}}
                                {{ Form::open(array('url' => route('admin.purchase_orders.quickgrn', $pso->id),'class'=>'form-inline-block disabled','onsubmit'=>'confirmSubmit(this);return false;')) }}
                                @method('POST')
                                {{--Genereate hidden fields--}}
                                {{Form::hidden('secret',$secret)}}
                                <button type="submit" data-toggle="tooltip" @if(($product->distributor_price <= 0)) title="Set Distributor Price to enable this" @else title="This will create GRN for all items is this purchase order" @endif  class="btn btn-default btn-sm {{($product->distributor_price <= 0)?'disabled':''}}">Quick GRN <i class="fa fa-bolt"></i></button>
                                {{Form::close()}}
                                {{--Cancel Form--}}
                                {{ Form::open(array('url' => route('admin.purchase_orders.destroy', $pso->id),'class'=>'form-inline-block','onsubmit'=>'confirmSubmit(this);return false;')) }}
                                @method('DELETE')
                                {{Form::hidden('status',\App\PurchaseOrder::cancel)}}
                                {{Form::submit('cancel',['class'=>'btn btn-outline-danger list-inline-item btn-sm'])}}
                                {{Form::close()}}
                                </td>
                        </tr>
                            @endforeach
                    @endforeach
                </table>
                    @else
                    <p class="help-block mt-5">{{trans('global.no_entries_in_table')}}</p>
                @endif
            </div>
        </div>
        <div class="panel panel-default p-1">
            <div class="panel-title">
                <h5>{{trans('global.received_items')}}</h5>
            </div>
            <div class="panel-body">
                @if($completedPurchaseOrders->count())
                <table class="table table-condensed mt-5">
                    <tr>
                        <th>Batch No</th>
                        <th>Expiry Date</th>
                        <th>Received Units</th>
                        <th class="text-center">Unit price</th>
                        <th>Total Price</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Received Date</th>
                        <th class="text-center">Requested Date</th>
                        <th>&nbsp;</th>
                    </tr>
                    @foreach($completedPurchaseOrders as $so)
                        @php
                            foreach ($so->purchaseOrderItems as $item){
                                if($item->product_id = $product->id){
                                    $so->item = $item;
                                }
                            }
                        @endphp
                        <tr>
                            <td>#{{$so->batch_no}}</td>
                            <td>{{$so->item->expiry_date}}</td>
                            <td>@currency($so->item->unit_price)</td>
                            <td class="text-center">{{$so->item->received_units}}</td>
                            <td>@currency($so->item->total_price)</td>
                            <td class="text-center">{{$so->status}}</td>
                            <td class="text-center">{{$so->received_date}}</td>
                            <td class="text-center">{{$so->created_at}}</td>
                        </tr>
                    @endforeach
                </table>
                    @else
                    <p class="help-block">{{trans('global.no_entries_in_table')}}</p>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('scriptbody')
    $('[data-toggle="tooltip"]').tooltip();
@endsection()