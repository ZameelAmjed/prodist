@extends('layouts.admin')
@section('content')
    @include('partials.breadcrumb',['links'=>[
['name'=>'Purchase Orders','url'=>route('admin.purchase_orders.index')],
['name'=>'Create','url'=>route('admin.purchase_orders.create')]
],
'pageimage'=>'inventory.svg'])
    @include('partials.backbutton')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.purchase_order.title_singular') }}
        </div>
        <div class="card-body">
            <form action="{{ route("admin.purchase_orders.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{Form::hidden('bulk',true)}}
                <div class="form-group {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
                    <label for="supplier">{{ trans('cruds.products.fields.supplier') }}</label>
                    <select name="supplier_id" class="form-control" v-model="supplier">
                        @foreach($suppliers as $supplier)
                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group{{ $errors->has('supplier_ref_code') ? 'has-error' : '' }}">
                    <label for="supplier_ref_code">{{ trans('cruds.purchase_order.fields.supplier_ref_code') }}</label>
                    <input class="form-control col-2" name="supplier_ref_code" id="supplier_ref_code" type="text">
                    <p class="help-block">{{trans('cruds.purchase_order.fields.supplier_ref_code_helper')}}</p>
                </div>
                <div>
                    <products-purchase-order :errors="{{ json_encode($errors->toArray(),JSON_FORCE_OBJECT)}}" :supplier="supplier" :old-name="{{json_encode(old('name',0))}}" :old-requested-units="{{ json_encode(old('requested_units',[]))}}"></products-purchase-order>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.create') }}">
                </div>
            </form>


        </div>
    </div>
@endsection