@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Products','url'=>route('admin.products.index')]
],
'pageimage'=>'box.svg'])
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.products.title_singular') }} {{ trans('global.list') }}
        <div class="pull-right">
            <form method="get">
                <input type="text" id="model" name="model" placeholder="Search Model" class="form-control col-md-12" value="{{old('model','')}}" required>
            </form>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.products.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.products.fields.model') }}
                        </th>
                        <th>
                            {{ trans('cruds.products.fields.series') }}
                        </th>
                        <th>
                            {{ trans('cruds.products.fields.points') }}
                        </th>
                        <th>
                            {{ trans('cruds.products.fields.active') }}
                        </th>
                        <th>
                            {{ trans('cruds.products.fields.last_barcode') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                        <tr data-entry-id="{{$product->id}}">
                            <td>
                                {{$product->product_name}}
                            </td>
                            <td>
                                {{$product->model}}
                            </td>
                            <td>
                                {{$product->series}}
                            </td>
                            <td class="text-center">
                                {{$product->points}}
                            </td>
                            <td>
                                {{$product->units_active}}
                            </td>
                            <td>
                                {{$product->last_barcode}}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.products.show', $product->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.products.edit', $product->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$products->links()}}
        </div>


    </div>
</div>
@endsection

@section('nav-item')
    @component('partials.navitem')
        @slot('links')
            @can('users_manage')
                <a class="nav-item nav-link" href="{{route('admin.products.create')}}"><i class="fa fa-plus"></i> {{ trans('global.add') }} {{ trans('cruds.products.title_singular') }}</a>
            @endcan
        @endslot
    @endcomponent
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        $.extend($.fn.dataTable.defaults, {
            buttons: [ 'copy', 'csv', 'excel' ]
        });
  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection