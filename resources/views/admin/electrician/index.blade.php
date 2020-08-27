@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Electrician','url'=>route('admin.electrician.index')],
],
'pageimage'=>'handyman.svg'])
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.electrician.title_singular') }} {{ trans('global.list') }}
        <div class="pull-right">
            <form method="get">
                <input type="text" id="nic" name="nic" placeholder="Search NIC or Phone" class="form-control col-md-12" value="{{old('nic',request()->get('nic'))}}" required>
            </form>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.electrician.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.electrician.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.electrician.fields.telephone') }}
                        </th>
                        <th>
                            {{ trans('cruds.electrician.fields.celebration') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($electricians as $key => $electrician)
                        <tr data-entry-id="{{$electrician->id}}">
                            <td>
                                {{$electrician->id}}
                            </td>
                            <td>
                                {{$electrician->name}}
                                {!! ($electrician->status==='reject') ? trans('global.label_reject'):'' !!}
                                {!! ($electrician->status==='pending') ? trans('global.label_pending'):'' !!}
                            </td>
                            <td>
                                {{$electrician->telephone}}
                            </td>
                            <td>
                                {{$electrician->celebration}}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.electrician.show', $electrician->id) }}">
                                    {{ trans('global.view') }}
                                </a>

                                <a class="btn btn-xs btn-info" href="{{ route('admin.electrician.edit', $electrician->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                @cannot('sales_executive')

                                <form action="{{ route('admin.electrician.destroy', $electrician->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>

                                <form action="{{ route('admin.electrician.update', $electrician->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PATCH">
                                    @if($electrician->status==='active')
                                        <input type="hidden" name="status" value="reject">
                                        <input type="submit" class="btn btn-xs btn-warning" value="{{ trans('global.reject') }}">
                                        @else
                                        <input type="hidden" name="status" value="active">
                                        <input type="submit" class="btn btn-xs btn-success" value="{{ trans('global.activate') }}">
                                    @endif
                                </form>
@endcannot
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$electricians->links('pagination.default')}}
    </div>
</div>
@endsection

@section('nav-item')
@component('partials.navitem')
    @slot('links')
            <a class="btn nav-item nav-link navbar-btn" href="{{route('admin.electrician.create')}}">
                <i class="fa fa-plus"></i> Add Electrician
            </a>
    @endslot
@endcomponent
@endsection

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('users_manage')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.users.mass_destroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

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