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
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Supplier:</strong> {{$supplierOrder->supplier->name}}</td>
                        <td><strong>{{ trans('cruds.supplier_order.fields.created_at') }}:</strong> {{$supplierOrder->created_at}}</td>
                        <td><strong>Status:</strong> {!! trans('global.'.$supplierOrder->status) !!}</td>
                    </tr>
                    <tr>
                        <td><label>Batch No:</label> <input class="form-control" name="batch_no"/></td>
                        <td><label>Invoice No:</label> <input class="form-control" name="invoice_no"/></td>
                        <td><label>Recived Date:</label> <input class="form-control" type="date" name="received_date"></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <strong>Items</strong>
                            <grn-items-update :fields="{{json_encode($supplierOrder->supplierOrderItems)}}"></grn-items-update>
                        </td>
                    </tr>
                </table>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </form>
        </div>
    </div>
@endsection