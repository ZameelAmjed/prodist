@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Payment Requests','url'=>route('admin.payments.requests')],
],
'pageimage'=>'request.svg'])
@section('content')
@can('users_manage')
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('global.payment_request') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        @if($paymentRequests->count())
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
                        {{ trans('cruds.payments.fields.created_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.status') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($paymentRequests as $key => $paymentRequest)
                    <td>
                        {{ str_pad($paymentRequest->id,5,0,STR_PAD_LEFT) }}
                    </td>
                    <td>
                        {{ $paymentRequest->electrician->name }}
                    </td>
                    <td>
                        {{ number_format($paymentRequest->amount,2) }}
                    </td>
                    <td>
                        {{ $paymentRequest->created_at }}
                    </td>
                    <td>
                        {{ $paymentRequest->status }}
                    </td>
                    <td>
                        <a class="btn btn-xs btn-primary text-light" href="{{route('admin.payments.create',['electrician'=>$paymentRequest->electrician->id])}}">Mark Complete</a>
                        <a class="btn btn-xs btn-default" href="{{route('admin.payments.generate',['id'=>$paymentRequest->electrician->id,'payable_points'=>$paymentRequest->amount])}}"><i class="fa fa-download"></i></a>
                    </td>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $paymentRequests->links() }}
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