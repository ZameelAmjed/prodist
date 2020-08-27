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
            <div class="form-group {{ $errors->has('product_name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.products.fields.name') }}*</label>
                <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name', isset($product) ? $product->product_name : '') }}" required>
                @if($errors->has('product_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('product_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.products.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('model') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.products.fields.model') }}</label>
                <input readonly type="text" id="model" name="model" class="form-control" value="{{ old('model', isset($product) ? $product->model : '') }}" required>
                @if($errors->has('model'))
                    <em class="invalid-feedback">
                        {{ $errors->first('model') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.products.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.products.fields.category') }}</label>
                <input type="text" id="category" name="category" class="form-control" value="{{ old('category', isset($product) ? $product->category : '') }}" required>
                @if($errors->has('category'))
                    <em class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('series') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.products.fields.series') }}</label>
                <input type="text" id="series" name="series" class="form-control" value="{{ old('series', isset($product) ? $product->series : '') }}" required>
                @if($errors->has('series'))
                    <em class="invalid-feedback">
                        {{ $errors->first('series') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.products.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('textcode') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.products.fields.textcode') }}</label>
                <input readonly type="text" id="textcode" name="textcode" class="form-control" value="{{ old('textcode', isset($product) ? $product->textcode : '') }}" required>
                @if($errors->has('textcode'))
                    <em class="invalid-feedback">
                        {{ $errors->first('textcode') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.products.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.products.fields.description') }}</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description', isset($product) ? $product->description : '') }}" required>
                @if($errors->has('description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.products.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('points') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.products.fields.points') }}</label>
                <input type="number" step="0.01" id="points" name="points" class="form-control" value="{{ old('points', isset($product) ? $product->points : '') }}" required>
                @if($errors->has('points'))
                    <em class="invalid-feedback">
                        {{ $errors->first('points') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.products.fields.name_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection