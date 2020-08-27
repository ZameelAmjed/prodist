@extends('layouts.admin')
@section('content')
@include('partials.backbutton')
@include('partials.breadcrumb',['links'=>[
['name'=>'Electrician','url'=>route('admin.electrician.index')],
['name'=>'Create','url'=>route('admin.electrician.create')]
],
'pageimage'=>'handyman.svg'])
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.electrician.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.electrician.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($electrician) ? $electrician->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('nic') ? 'has-error' : '' }}">
                <label for="nic">{{ trans('global.nic') }}*</label>
                <input type="text" id="nic" name="nic" class="form-control" value="{{ old('nic', isset($electrician) ? $electrician->nic : '') }}">
                @if($errors->has('nic'))
                    <em class="invalid-feedback">
                        {{ $errors->first('nic') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                <label for="date_of_birth">{{ trans('cruds.electrician.fields.dob') }}</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control date" value="{{ old('date_of_birth', isset($electrician) ? $electrician->date_of_birth : '') }}">
                @if($errors->has('date_of_birth'))
                    <em class="invalid-feedback">
                        {{ $errors->first('date_of_birth') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('telephone') ? 'has-error' : '' }}">
                <label for="telephone">{{ trans('cruds.electrician.fields.telephone') }}*</label>
                <input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone', isset($electrician) ? $electrician->telephone : '') }}">
                @if($errors->has('telephone'))
                    <em class="invalid-feedback">
                        {{ $errors->first('telephone') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('celebration') ? 'has-error' : '' }}">
                <label for="celebration">{{ trans('cruds.electrician.fields.celebration') }}</label>
                <select type="text"
                       id="celebration"
                       name="celebration"
                       class="form-control"
                       value="{{ old('celebration', isset($electrician) ? $electrician->celebration : '') }}">
                    <option value="Christmas">Christmas</option>
                    <option value="Eid">Eid</option>
                    <option value="Vesak">Vesak</option>
                    <option value="Pongal">Pongal</option>
                </select>
                @if($errors->has('celebration'))
                    <em class="invalid-feedback">
                        {{ $errors->first('celebration') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('language') ? 'has-error' : '' }}">
                <label for="language">{{ trans('cruds.electrician.fields.language') }}</label>
                <select type="text"
                       id="language"
                       name="language"
                       class="form-control"
                       value="{{ old('language', isset($electrician) ? $electrician->language : '') }}">
                    <option value="English">English</option>
                    <option value="Tamil">Tamil</option>
                    <option value="Sinhala">Sinhala</option>
                </select>
                @if($errors->has('celebration'))
                    <em class="invalid-feedback">
                        {{ $errors->first('celebration') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="panel panel-default mt-5">
                <div class="panel-title">
                    <h6>{{trans('global.address')}}</h6>
                </div>
                <div class="panel-body">
                    <div class="row m-0 p-0">
                        <div class="col-4 m-0 p-0">
                            <div class="form-group {{ $errors->has('block') ? 'has-error' : '' }}">
                                <label for="block">{{ trans('global.block') }}</label>
                                <input type="text" id="block" name="block" class="form-control" value="{{ old('block', isset($electrician) ? $electrician->block : '') }}">
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
                                <input type="text" id="street" name="street" class="form-control" value="{{ old('street', isset($electrician) ? $electrician->street : '') }}">
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
                    <auto-city
                            v-on:city-value="city = $event"
                            province="{{ strtolower(trans('global.province')) }}"
                            city="{{ strtolower(trans('global.city') )}}"
                            :error="{city:{{ $errors->has('city') ? 'has-error' : '""' }},province:{{ $errors->has('province') ? 'has-error' : '""' }}}"
                    ></auto-city>
                    <div class="row p-0 m-0">
                        <div class="col-4 p-0 m-0">
                           {{-- <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                                <label for="city">{{ trans('global.city') }}</label>
                                <input ref="city" type="text" id="city" name="city" class="form-control" value="{{ old('city', isset($electrician) ? $electrician->city : '') }}">
--}}
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
                           {{-- <div class="form-group {{ $errors->has('province') ? 'has-error' : '' }}">
                                <label for="province">{{ trans('global.province') }}</label>
                                <input type="text" id="province" name="province" class="form-control" value="{{ old('province', isset($electrician) ? $electrician->province : '') }}">
                               --}} @if($errors->has('province'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('province') }}
                                    </em>
                                @endif
                                <p class="helper-province">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
            </div>

            {{--@include('admin.electrician.bank')--}}
            <auto-bank></auto-bank>
           <auto-sales-demographics></auto-sales-demographics>
            <div class="panel panel-default mt-5">
                <div class="panel-title"><h6>{{trans('global.photo')}}</h6></div>
                <div class="panel-body">
                    <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                        <label for="photo">{{ trans('cruds.electrician.fields.photo') }}</label>
                        <input type="file" id="photo" name="photo" value="{{ old('photo', isset($electrician) ? $electrician->photo : '') }}">
                        @if($errors->has('photo'))
                            <em class="invalid-feedback">
                                {{ $errors->first('photo') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.name_helper') }}
                        </p>
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