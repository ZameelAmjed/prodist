@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Dealers','url'=>route('admin.dealers.index')],
['name'=>'View','url'=>route('admin.dealers.show',$dealer)],
],
'pageimage'=>'mover-truck.svg'])
@section('content')
@include('partials.backbutton')
<div class="card">
    <div class="card-header">
        {{trans('cruds.dealer.title_singular')}} </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.dealer.fields.business_name') }}
                    </th>
                    <td>
                        {{$dealer->business_name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dealer.fields.name') }}
                    </th>
                    <td>
                         {{$dealer->name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dealer.fields.dealer_type') }}
                    </th>
                    <td>
                        <label class="badge badge-warning {{($dealer->dealer_type=='primary')?'bg-info':'bg-warning'}}">{{$dealer->dealer_type}}</label>
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dealer.fields.telephone') }}
                    </th>
                    <td>
                        {{$dealer->telephone}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dealer.fields.mobile') }}
                    </th>
                    <td>
                        {{$dealer->mobile}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.address') }}
                    </th>
                    <td>
                       {{$dealer->block}},
                        {{$dealer->street}},
                        {{$dealer->city}},
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dealer.fields.area') }}
                    </th>
                    <td>
                       {{$dealer->area}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.dealer.fields.region') }}
                    </th>
                    <td>
                        {{$dealer->region}}
                    </td>
                </tr>
                </tbody>
            </table>
            <a href="{{route('admin.dealers.edit',$dealer->id)}}" class="btn btn-xs btn-info">Edit</a>
        </div>
    </div>
</div>
@endsection