@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Reports','url'=>route('admin.reports.electrician')],
],
'pageimage'=>'report.svg'])
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.dealer.title_singular') }} {{ trans('global.reports') }}
        </div>
        <div class="card-body">
            <form method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="business_name">Business Name</label>
                    <input name="business_name" class="form-control" type="text" value="{{request()->input('business_name')}}">
                </div>
                <div class="col-md-4">
                    <label for="name">Name</label>
                    <input name="name" class="form-control" type="text" value="{{request()->input('name')}}">
                </div>
            </div>
                <div class="row">
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
                        <label for="area">Area</label>
                        <autocomplete ref="autocomplete"
                                      source="{{url('/api/getarea?area=')}}"
                                      input-class="form-control"
                                      results-value="_id"
                                      results-display="_id"
                                      clear-button-icon=""
                                      placeholder="type to search"
                                      name="area"
                                      id="area"
                        ></autocomplete>
                    </div>
                    <div class="col-md-4">
                        <label for="region">Region</label>
                        <autocomplete ref="autocomplete"
                                      source="{{url('/api/getarea?region=')}}"
                                      input-class="form-control"
                                      results-value="_id"
                                      results-display="_id"
                                      clear-button-icon=""
                                      placeholder="type to search"
                                      name="region"
                                      id="region"
                        ></autocomplete>
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
        @if(count($dealers))
        <table class="table table-condensed">
            <thead>
            <th>Business Name</th>
            <th>Name</th>
            <th>Telephone</th>
            <th>Dealer Type</th>
            <th>Joined Date</th>
            </thead>
            <tbody>
        @foreach($dealers as $dealer)
            <tr>
                <td><a href="{{route('admin.dealers.show', $dealer->id)}}">{{$dealer->business_name}}</a></td>
                <td>{{$dealer->name}}</td>
                <td>{{Helper::sanitizeLkTelephone($dealer->telephone)}}</td>
                <td>{{$dealer->dealer_type}}</td>
               <td>{{$dealer->created_at}}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
        <div class="text-xs-center text-center">
            {{$dealers->links('pagination.default')}}
        </div>

        @else
            <div class="alert alert-info mb-0">{{trans('global.search_nothing_found')}}</div>
        @endif
    </div>

@endsection