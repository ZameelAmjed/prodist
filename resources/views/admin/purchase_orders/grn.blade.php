@extends('layouts.admin')
@section('content')
    @include('partials.breadcrumb',['links'=>[
['name'=>'Purchase Orders','url'=>route('admin.purchase_orders.index')],
['name'=>'GRN','url'=>route('admin.purchase_orders.grn',$purchaseOrder)]
],
'pageimage'=>'inventory.svg'])
    @include('partials.backbutton')
    <div class="card">
        <div class="card-header">
            GRN for Purchase Order <strong>#{{$purchaseOrder->uid}}</strong>
        </div>

        <div class="card-body">
            <form action="{{route('admin.purchase_orders.update', $purchaseOrder->id)}}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                {{Form::hidden('grn',true)}}
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Supplier:</strong> {{$purchaseOrder->supplier->name}}</td>
                        <td><strong>{{ trans('cruds.purchase_order.fields.created_at') }}:</strong> {{$purchaseOrder->created_at}}</td>
                        <td><strong>Status:</strong> {!! trans('global.'.$purchaseOrder->status) !!}</td>
                    </tr>
                    <tr>
                        <td><label>Batch No:</label> <input class="form-control" name="batch_no"/></td>
                        <td class="{{ $errors->has('invoice_no') ? 'has-error' : '' }}"><label>Invoice No:</label> <input class="form-control" name="invoice_no"/></td>
                        <td><label>Received Date:</label> <input class="form-control" type="date" name="received_date" value="{{\Carbon\Carbon::today()->format('Y-m-d')}}"></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <strong>Items</strong>
                            <grn-items-update
                                    :errors="{{ json_encode($errors->toArray(),JSON_FORCE_OBJECT)}}"
                                    :fields="{{json_encode($purchaseOrder->purchaseOrderItems)}}"
                                    :old="{{json_encode(old(null,0))}}"
                            ></grn-items-update>
                        </td>
                    </tr>
                </table>
                <input class="btn btn-primary" type="submit" value="{{ trans('global.save') }}">
            </form>
        </div>
    </div>
@endsection