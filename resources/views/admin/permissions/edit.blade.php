@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Permissions','url'=>route('admin.permissions.index')],
['name'=>'Edit','url'=>route('admin.permissions.edit',$permission)]
],'pageimage'=>'security.svg'])
@include('partials.backbutton')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.permissions.update", [$permission->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.permission.fields.title') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($permission) ? $permission->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.permission.fields.title_helper') }}
                </p>
            </div>
            <div>
                @if($permission->name == 'super_admin')
                    @can('super_admin')
                        <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                    @endcan
                    @else
                        <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                @endif
            </div>
        </form>


    </div>
</div>
@endsection