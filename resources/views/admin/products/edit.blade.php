@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Products','url'=>route('admin.products.index')],
['name'=>'Edit','url'=>route('admin.products.edit', $product)],
],
'pageimage'=>'box.svg'])
@include('partials.backbutton')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.products.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.products.update", [$product->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
            <div class="form-group {{ $errors->has('supplier_id') ? 'has-error' : '' }}">
                <label for="supplier_id">{{ trans('cruds.products.fields.supplier') }}</label>
                <select name="supplier_id" class="form-control">
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}" {{($supplier->id==$product->supplier_id)?'selected':''}}>{{$supplier->name}}</option>
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