@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Dealers','url'=>route('admin.dealers.index')],
],
'pageimage'=>'mover-truck.svg'])
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.dealer.title_singular') }} {{ trans('global.list') }}
        <div class="pull-right">
            <form method="get">
                <input type="text" id="name" name="name" placeholder="Search Name" class="form-control col-md-12" value="{{old('name','')}}" required>
            </form>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.dealer.fields.business_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.dealer.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.dealer.fields.telephone') }}
                        </th>
                        <th>
                            Area
                        </th>
                        <th>
                            Region
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dealers as $key => $dealer)
                        <tr data-entry-id="{{$dealer->id}}">
                            <td>
                                {{$dealer->business_name}}
                            </td>
                            <td>
                                {{$dealer->name}}
                            </td>
                            <td>
                                {{$dealer->telephone}}
                            </td>
                            <td>
                                {{$dealer->area}}
                            </td>
                            <td>
                                {{$dealer->region}}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.dealers.show', $dealer->id) }}">
                                    {{ trans('global.view') }}
                                </a>

                                <a class="btn btn-xs btn-info" href="{{ route('admin.dealers.edit', $dealer->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                @cannot('sales_executive')
                                <form method="post" class="" action="{{ route('admin.dealers.destroy', $dealer->id) }}" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">{{ trans('global.delete') }}</button>
                                </form>
                                @endcannot
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
{{$dealers->links()}}

    </div>
</div>
@endsection

@section('nav-item')
    @component('partials.navitem')
        @slot('links')
            @can('users_manage')
                <a class="nav-item nav-link" href="{{route('admin.dealers.create')}}"><i class="fa fa-plus"></i> Add Dealer</a>
            @endcan
            @endslot
    @endcomponent
@endsection