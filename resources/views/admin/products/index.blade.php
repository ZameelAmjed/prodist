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
            <div class="form-inline-block">
                {{Form::open(['method'=>'get','class'=>'form-inline'])}}
                <div class="form-group">
                    {{Form::text('search',request('search',''),['placeholder'=>'Search', 'class'=>'form-control col-md-12'])}}
                </div>
                <div class="form-group ml-2">
                    <label for="supplier">
                    {{Form::checkbox('supplier','true',request('supplier',false),['class'=>'checkbox','id'=>'supplier'])}}
                         Search By Supplier
                    </label>
                </div>
                {{Form::close()}}
            </div>

        </div>
    </div>

    <div class="card-body">
        @if($products->count())
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.products.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.products.fields.stock') }}
                        </th>
                        <th>
                            {{ trans('cruds.supplier.title_singular') }}
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
                                {{$product->name}}
                            </td>
                            <td>
                                {{$product->stock}}
                            </td>
                            <td>
                                {{$product->supplier->name}}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.products.show', $product->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.products.edit', $product->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="confirmSubmit(this);return false;" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$products->appends($_GET)->links()}}
        </div>
        @else
             <empty-results message="{{trans('global.no_entries_in_table')}}"></empty-results> 
        @endif
    </div>
</div>
@endsection

@section('nav-item')
    @component('partials.navitem')
        @slot('links')
            @can('users_manage')
                <a class="nav-item nav-link mr-5" href="{{route('admin.products.create')}}"><i class="fa fa-plus"></i> {{ trans('global.add') }} {{ trans('cruds.products.title_singular') }}</a>
                <a class="nav-item nav-link" href="{{route('admin.purchase_orders.index')}}"><i class="fa fa-plus"></i> Add Purchase Order</a>
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