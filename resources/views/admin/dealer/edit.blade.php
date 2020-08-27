@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Dealers','url'=>route('admin.dealers.index')],
['name'=>'Edit','url'=>route('admin.dealers.edit',$dealer)],
],
'pageimage'=>'mover-truck.svg'])
@section('content')
@include('partials.backbutton',['back'=>route('admin.dealers.index')])
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.dealer.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.dealers.update", $dealer) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('business_name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.dealer.fields.business_name') }}*</label>
                <input type="text" id="business_name" name="business_name" class="form-control" value="{{ old('business_name', isset($dealer) ? $dealer->business_name : '') }}" required>
                @if($errors->has('business_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('business_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($dealer) ? $dealer->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('dealer_type') ? 'has-error' : '' }}">
                <label for="dealer_type">{{ trans('cruds.dealer.fields.dealer_type') }}*</label>
                <div class="input-group">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="dealer_type_primary" name="dealer_type" value="primary" {{(old('dealer_type') == 'primary') ? 'checked' : ''}} {{(!old('dealer_type') && ($dealer->dealer_type=='primary')) ? 'checked' : ''}}>
                        <label class="custom-control-label" for="dealer_type_primary">Primary</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="dealer_type_secondary" name="dealer_type" value="secondary" {{(old('dealer_type') == 'secondary') ? 'checked' : ''}} {{(!old('dealer_type') && ($dealer->dealer_type=='secondary')) ? 'checked' : ''}}>
                        <label class="custom-control-label" for="dealer_type_secondary">Secondary</label>
                    </div>
                </div>
                @if($errors->has('business_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('business_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('telephone') ? 'has-error' : '' }}">
                <label for="telephone">{{ trans('cruds.dealer.fields.telephone') }}*</label>
                <input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone', isset($dealer) ? $dealer->telephone : '') }}">
                @if($errors->has('telephone'))
                    <em class="invalid-feedback">
                        {{ $errors->first('telephone') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                <label for="mobile">{{ trans('cruds.dealer.fields.mobile') }}*</label>
                <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', isset($dealer) ? $dealer->mobile : '') }}">
                @if($errors->has('mobile'))
                    <em class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="panel panel-default mt-5">
                <div class="panel-title"><h6>{{trans('global.address')}}</h6></div>
                <div class="panel-body">
                    <div class="row m-0 p-0">
                        <div class="col-4 m-0 p-0">
                            <div class="form-group {{ $errors->has('block') ? 'has-error' : '' }}">
                                <label for="block">{{ trans('global.block') }}</label>
                                <input type="text" id="block" name="block" class="form-control" value="{{ old('block', isset($dealer) ? $dealer->block : '') }}">
                                @if($errors->has('block'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('block') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group {{ $errors->has('street') ? 'has-error' : '' }}">
                                <label for="street">{{ trans('global.street') }}</label>
                                <input type="text" id="street" name="street" class="form-control" value="{{ old('street', isset($dealer) ? $dealer->street : '') }}">
                                @if($errors->has('street'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('street') }}
                                    </em>
                                @endif
                                <p class="helper-street">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row p-0 m-0">
                        <div class="col-4 p-0 m-0">
                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                                <label for="city">{{ trans('global.city') }}</label>
                                <autocomplete ref="autocomplete"
                                              source="{{env('MIX_APP_URL')}}/api/getarea?area="
                                              input-class="form-control"
                                              initial-display="{{ old('city', isset($dealer) ? $dealer->city : '') }}"
                                              initial-value="{{ old('city', isset($dealer) ? $dealer->city : '') }}"
                                              results-value="_id"
                                              results-display="_id"
                                              clear-button-icon=""
                                              placeholder="type to search"
                                              name="city"
                                              id="city"
                                ></autocomplete>
                                @if($errors->has('city'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('city') }}
                                    </em>
                                @endif
                                <p class="helper-city">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-4">
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-5">
                <div class="panel-title"><h6>Sales Demographics</h6></div>
                <div class="panel-body">
                    <div class="row m-0 p-0">
                        <div class="col-4 m-0 p-0">
                            <div class="form-group {{ $errors->has('area') ? 'has-error' : '' }}">
                                <label for="area">Area</label>
                                <autocomplete ref="autocomplete2"
                                              source="{{env('MIX_APP_URL')}}/api/getarea?area="
                                              input-class="form-control"
                                              initial-display="{{ old('area', isset($dealer) ? $dealer->area : '') }}"
                                              initial-value="{{ old('area', isset($dealer) ? $dealer->area : '') }}"
                                              results-value="_id"
                                              results-display="_id"
                                              clear-button-icon=""
                                              placeholder="type to search"
                                              @selected="city = $event.selectedObject.region"
                                              name="area"
                                              id="area"
                                ></autocomplete>
                                @if($errors->has('area'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('area') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
                                <label for="region">Region</label>
                                <input :value="(city)?city:'{{old('region', @$dealer->region )}}'" type="text" id="region" name="region" class="form-control">
                                @if($errors->has('region'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('region') }}
                                    </em>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        window.__FORM__ = 'my_default_value'
    </script>

@endsection