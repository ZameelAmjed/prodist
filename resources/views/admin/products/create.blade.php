@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Products','url'=>route('admin.products.index')],
['name'=>'Add','url'=>route('admin.products.create')],
],
'pageimage'=>'box.svg'])
@section('content')
@include('partials.backbutton')
    <div class="card">
        <div class="card-header">
            {{ trans('global.add') }} {{ trans('cruds.products.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.products.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.products.fields.name') }}*</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($product) ? $product->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.products.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('brand') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.products.fields.brand') }}</label>
                    <autocomplete ref="autocomplete"
                                  source="{{url('api/getbrands')}}?name="
                                  input-class="form-control"
                                  initial-display="{{ old('brand', isset($product) ? $product->brand : '') }}"
                                  initial-value="{{ old('brand', isset($product) ? $product->brand : '') }}"
                                  results-value="brand"
                                  results-display="brand"
                                  clear-button-icon=""
                                  placeholder="type to search"
                                  name="brand"
                                  id="brand"
                    ></autocomplete>
                    @if($errors->has('brand'))
                        <em class="invalid-feedback">
                            {{ $errors->first('brand') }}
                        </em>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                    <label for="code">{{ trans('cruds.products.fields.code') }}</label>
                    <input class="form-control col-2" name="code" id="code" type="text">
                </div>
                <div class="form-group {{ $errors->has('retail_price') ? 'has-error' : '' }}">
                    <label for="retail_price">{{ trans('cruds.products.fields.retail_price') }}</label>
                    <input class="form-control col-2" name="retail_price" id="retail_price" type="number" step="0.01">
                </div>
                <div class="form-group {{ $errors->has('distributor_price') ? 'has-error' : '' }}">
                    <label for="distributor_price">{{ trans('cruds.products.fields.distributor_price') }}</label>
                    <input class="form-control col-2" name="distributor_price" id="distributor_price" type="number" step="0.01">
                </div>
                <div class="form-group">
                    <upload-product-image></upload-product-image>
                </div>
                <div class="form-group {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
                    <label for="supplier">{{ trans('cruds.products.fields.supplier') }}</label>
                   <select name="supplier_id" class="form-control">
                       @foreach($suppliers as $supplier)
                           <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                           @endforeach
                   </select>
                </div>
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>


        </div>
    </div>
@endsection