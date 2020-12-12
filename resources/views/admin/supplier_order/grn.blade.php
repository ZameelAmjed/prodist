@extends('layouts.admin')
@section('content')
    @include('partials.breadcrumb',['links'=>[
['name'=>'Supplier Order','url'=>route('admin.supplier_order.index')],
['name'=>'GRN','url'=>route('admin.supplier_order.grn',$supplierOrder)]
],
'pageimage'=>'inventory.svg'])
    @include('partials.backbutton')
    <div class="card">
        <div class="card-header">
            GRN for Purchase Order <strong>#{{$supplierOrder->uid}}</strong>
        </div>

        <div class="card-body">
            <form action="{{route('admin.supplier_order.update', $supplierOrder->id)}}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                {{Form::hidden('grn',true)}}
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Supplier:</strong> {{$supplierOrder->supplier->name}}</td>
                        <td><strong>{{ trans('cruds.supplier_order.fields.created_at') }}:</strong> {{$supplierOrder->created_at}}</td>
                        <td><strong>Status:</strong> {!! trans('global.'.$supplierOrder->status) !!}</td>
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
                                    :fields="{{json_encode($supplierOrder->supplierOrderItems)}}"
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