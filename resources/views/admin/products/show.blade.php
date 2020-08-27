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
        {{trans('cruds.products.title_singular')}} </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.name') }}
                    </th>
                    <td>
                         {{$product->product_name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.model') }}
                    </th>
                    <td>
                       {{$product->model}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.category') }}
                    </th>
                    <td>
                        {{$product->category}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.series') }}
                    </th>
                    <td>
                        {{$product->series}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.description') }}
                    </th>
                    <td>
                       {{$product->description}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.points') }}
                    </th>
                    <td>
                        {{$product->points}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.issued') }}
                    </th>
                    <td>
                       {{$product->units_issued}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.active') }}
                    </th>
                    <td>
                        {{$product->units_active}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.products.fields.last_barcode') }}
                    </th>
                    <td>
                        {{$product->last_barcode}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel panel-default p-1">
            <div class="panel-title">
                <h5>{{trans('global.add_units')}}</h5>
            </div>
            <div class="panel-body">
                <form class="form-inline" action="{{ route("admin.products.add_units", [$product->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('{{ trans('global.areYouSureWarning') }}');">
                    @csrf
                    @method('POST')
                    <label for="product_name" class="">{{ trans('cruds.units.fields.no_of_units_text') }}*</label>
                    <div class="form-group {{ $errors->has('product_name') ? 'has-error' : '' }}" >
                         <input type="number" id="no_of_units" name="no_of_units" class="form-control"
                               value="{{ old('no_of_units','') }}" required>
                        <input class="btn btn-danger" type="submit" value="{{ trans('global.add') }}">
                    </div>
                    <div>
                        @if($errors->has('no_of_units'))
                            <em class="invalid-feedback">
                                {{ $errors->first('no_of_units') }}
                            </em>
                        @endif
                    </div>
                </form>

            </div>
        </div>
        <div class="panel panel-default p-1">
            <div class="panel-title">
                <h5>{{trans('global.print_code')}}</h5>
            </div>
            <div class="panel-body">
                <form class="form-inline" action="{{ route("admin.products.print_code", [$product->id]) }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    @method('POST')
                    <label for="start" class="">{{ trans('global.start') }}*</label>
                    <div class="form-group {{ $errors->has('start') ? 'has-error' : '' }}" >
                        <input type="number" id="start" name="start" class="form-control"
                               value="{{ old('start','') }}" required>
                        @if($errors->has('start'))
                            <em class="invalid-feedback invalid-feedback-inline-fix">
                                {{ $errors->first('start') }}
                            </em>
                        @endif
                    </div>
                    <label for="end" class="">{{ trans('global.end') }}*</label>
                    <div class="form-group {{ $errors->has('end') ? 'has-error' : '' }}" >
                        <input type="number" id="end" name="end" class="form-control"
                               value="{{ old('end','') }}" required >
                        @if($errors->has('end'))
                            <em class="invalid-feedback invalid-feedback-inline-fix">
                                {{ $errors->first('end') }}
                            </em>
                        @endif
                    </div>
                    <label class="m-3"> Excel</label>
                    <div class="form-group mr-3">
                        <input class="btn btn-danger mr-1" type="radio" name="type" value="excel">
                    </div>
                    <label class="m-3"> PDF</label>
                    <div class="form-group mr-3">
                        <input class="btn btn-danger" type="radio" name="type" value="pdf" checked>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="{{ trans('global.generate') }}">
                    </div>
                    <div>

                    </div>
                </form>

            </div>
        </div>


    </div>
</div>
@endsection