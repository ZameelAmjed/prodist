@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Payments','url'=>route('admin.payments.index')],
],
'pageimage'=>'money.svg'])
@section('content')
@can('users_manage')
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.payments.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        @if($payments->count())
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                <tr>
                    <th>
                        {{ trans('global.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.electrician.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.points') }}/{{trans('global.amount')}}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.transfer_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.payed_on') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.comment') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $key => $payment)
                    <tr data-entry-id="{{$payment->id}}">
                        <td>
                            {{$payment->id}}
                        </td>
                        <td>
                            {{$payment->electrician->name}}
                        </td>
                        <td>
                            {{number_format($payment->points,2)}}
                        </td>
                        <td>
                            {{$payment->transfer_type}}
                        </td>
                        <td>
                            {{$payment->payed_on}}
                        </td>
                        <td>
                            {{$payment->comment}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
        @else
        <div>{{trans('global.no_entries_in_table')}}</div>
        @endif
    </div>
</div>
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