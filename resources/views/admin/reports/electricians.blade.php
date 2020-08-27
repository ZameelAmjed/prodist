@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Reports','url'=>route('admin.reports.electrician')],
],
'pageimage'=>'report.svg'])
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.electrician.title_singular') }} {{ trans('global.reports') }}
        </div>
        <div class="card-body">
            <form method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="nic">NIC/Telephone</label>
                    <input name="nic" class="form-control" type="text" value="{{request()->input('nic')}}">
                </div>
                <div class="col-md-4">
                    <label for="name">City</label>
                    <autocomplete ref="autocomplete"
                                  source="{{url('/api/getarea?area=')}}"
                                  input-class="form-control"
                                  results-value="_id"
                                  results-display="_id"
                                  clear-button-icon=""
                                  placeholder="type to search"
                                  name="city"
                                  id="city"
                    ></autocomplete>
                </div>
                <div class="col-md-4">
                    <label for="province">Region</label>
                    <autocomplete ref="autocomplete"
                                  source="{{url('/api/getarea?region=')}}"
                                  input-class="form-control"
                                  results-value="_id"
                                  results-display="_id"
                                  clear-button-icon=""
                                  placeholder="type to search"
                                  name="province"
                                  id="province"
                    ></autocomplete>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="celebration">{{ trans('cruds.electrician.fields.celebration') }}</label>
                        <select type="text"
                                id="celebration"
                                name="celebration"
                                class="form-control">
                            <option value="" >--</option>
                            <option value="christmas" {{(request()->get('celebration')=='christmas')?'selected':''}}>Christmas</option>
                            <option value="eid" {{(request()->get('celebration')=='eid')?'selected':''}}>Eid</option>
                            <option value="vesak" {{(request()->get('celebration')=='vesak')?'selected':''}}>Vesak</option>
                            <option value="pongal" {{(request()->get('celebration')=='pongal')?'selected':''}}>Pongal</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <label for="dealer">{{ trans('cruds.dealer.title_singular') }}</label>
                        <auto-input :data="{{Helper::getdealers()->toJson()}}" name="dealer" value="{{old('dealer',request()->get('dealer'))}}"></auto-input>
                    </div>
                </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="btn-group mt-4" data-toggle="buttons">
                        <label class="btn btn-default {{(request()->get('points')=='desc')?'active':''}}">
                            <input type="radio" name="points" id="option1" value="desc" {{(request()->get('points')=='desc')?'checked':''}} {{(request()->get('points')=='')?'checked':''}}> Points High to Low
                        </label>
                        <label class="btn btn-default {{(request()->get('points')=='asc')?'active':''}}">
                            <input type="radio" name="points" id="option2" value="asc" {{(request()->get('points')=='asc')?'checked':''}}>Points Low to High
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="btn-group mt-4" data-toggle="buttons">
                        <label class="btn btn-default {{(request()->get('points')=='fdesc')?'active':''}}">
                            <input type="radio" name="points" id="option1" value="fdesc" {{(request()->get('points')=='fdesc')?'checked':''}}>Payable High to Low
                        </label>
                        <label class="btn btn-default {{(request()->get('points')=='fasc')?'checked':''}}">
                            <input type="radio" name="points" id="option2" value="fasc" {{(request()->get('points')=='fasc')?'checked':''}}>Payable Low to High
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                    @can('super_admin')
                        <button name="export" value="excel" type="submit" class="btn btn-warning text-white mt-2">Export</button>
                    @endcan
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="card">
        @if(count($electricians))
        <table class="table table-condensed">
            <thead>
            <th>Name</th>
            <th>NIC</th>
            <th>Telephone</th>
            <th>Status</th>
            <th>Total Points</th>
            <th>Payable</th>
            <th>Joined Date</th>
            </thead>
            <tbody>
        @foreach($electricians as $electrician)
            <tr>
                <td><a href="{{route('admin.electrician.show', $electrician->id)}}">{{$electrician->name}}</a></td>
                <td>{{$electrician->nic}}</td>
                <td>{{Helper::sanitizeLkTelephone($electrician->telephone)}}</td>
                <td>{{$electrician->status}}</td>
                <td>{{$electrician->points}}</td>
                <td><a  href="{{route('admin.payments.create','&electrician='.$electrician->id)}}" class="">{{$electrician->float_points}}</a></td>
                <td>{{$electrician->created_at}}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
        <div class="text-xs-center text-center">
            {{$electricians->links('pagination.default')}}
        </div>

        @else
            <div class="alert alert-info mb-0">{{trans('global.search_nothing_found')}}</div>
        @endif
    </div>

@endsection