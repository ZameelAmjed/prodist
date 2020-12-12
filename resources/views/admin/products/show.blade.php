@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Products','url'=>route('admin.products.index')],
['name'=>'View','url'=>route('admin.products.show',$product)],
],
'pageimage'=>'box.svg'])
@section('content')
@include('partials.backbutton')
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
        <div class="panel panel-default p-1">
            <div class="panel-title">
                <h5>{{trans('global.request_items')}}</h5>
            </div>
            <div class="panel-body">
                {{ Form::open(array('url' => route('admin.supplier_order.store'),'class'=>'form-inline')) }}
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
                @if($pendingSupplierOrders->count())
                <table class="table table-condensed mt-5">
                    <tr>
                        <th>Purchase Order ID</th>
                        <th>Units</th>
                        <th>Requested Date</th>
                        <th>&nbsp;</th>
                    </tr>
                    @foreach($pendingSupplierOrders as $pso)
                        @foreach($pso->supplierOrderItems as $item)
                        <tr>
                            <td>{{$pso->uid}}</td>
                            <td>{{$item->requested_units}}</td>
                            <td>{{$pso->created_at}}</td>
                            <td>
                                {{ Form::open(array('url' => route('admin.supplier_order.destroy', $pso->id),'class'=>'form-inline','onsubmit'=>'confirmSubmit(this);return false;')) }}
                                @method('DELETE')
                                {{Form::hidden('status',\App\SupplierOrder::cancel)}}
                                {{Form::submit('cancel',['class'=>'btn btn-warning btn-xs'])}}
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
                @if($completedSupplierOrders->count())
                <table class="table table-condensed mt-5">
                    <tr>
                        <th>Batch No</th>
                        <th>Expiry Date</th>
                        <th>Received Units</th>
                        <th>Unit price</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Received Date</th>
                        <th>Requested Date</th>
                        <th>&nbsp;</th>
                    </tr>
                    @foreach($completedSupplierOrders as $so)
                        <tr>
                            <td>#{{$so->batch_no}}</td>
                            <td>{{$so->expiry_date}}</td>
                            <td>{{$so->received_units}}</td>
                            <td>{{$so->unit_price}}</td>
                            <td>{{$so->total_price}}</td>
                            <td>{{$so->status}}</td>
                            <td>{{$so->created_at}}</td>
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