@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Payments','url'=>route('admin.payment.index')],
],
'pageimage'=>'money.svg'])
@section('content')
@can('users_manage')
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.payments.title_singular') }} {{ trans('global.list') }}
        <div class="pull-right">
            <div class="pl-3 pr-3 form-inline-block">
                {{Form::open(['method'=>'get'])}}
                <label>Status &nbsp;</label>
                <div class="btn-group" role="group">
                    <button name="status" value="all" type="submit"
                            class="btn btn-default {{(request('status')=='all')?'active':''}} {{request('status')??'active'}}">
                        All
                    </button>
                    <button name="status" value="return" type="submit"
                            class="btn btn-default {{(request('status')=='return')?'active':''}}">{{trans('global.payments_return')}}
                    </button>
                    <button name="status" value="reject" type="submit"
                            class="btn btn-default {{(request('status')=='reject')?'active':''}}">{{trans('global.cheques_dishonor')}}
                    </button>
                    <a href="{{route('admin.payment.returncharges')}}"
                       class="btn btn-default ">{{trans('cruds.adjustment_charge.title')}}
                    </a>
                </div>
                {{Form::close()}}
            </div>
            <div class="pl-3 pr-3 form-inline-block">
                {{Form::open(['method'=>'get'])}}
                {{Form::text('search', old('search',request('search')), ['placeholder'=>'Store Name', 'class'=>'form-control'])}}
                {{Form::close()}}
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($payments->count())
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                <tr>
                    <th>
                        {{ trans('cruds.order.fields.invoice_id') }}
                    </th>
                    <th>
                        {{ trans('cruds.store.fields.business_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.payment_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.comment') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.payed_on') }}
                    </th>
                    <th>
                        {{ trans('cruds.payments.fields.amount') }}
                    </th>
                    <th>

                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $key => $payment)
                    <tr data-entry-id="{{$payment->id}}" class="{{($payment->status==\App\Payment::return ||$payment->status==\App\Payment::reject)?'table-danger':''}}">
                        <td>
                            <a href="{{route('admin.orders.show',$payment->order->id)}}">{{$payment->order->invoiceUid}}</a>
                        </td>
                        <td>
                            {{$payment->order->store->business_name}}
                        </td>
                        <td>
                            {{$payment->payment_type}}
                        </td>
                        <td>
                            <small>{{$payment->comment}}</small>
                        </td>
                        <td>
                            {{$payment->created_at}}
                            @if(strtotime($payment->realize_date)>strtotime('now'))
                                <span class="badge badge-info bg-info">Realizes on {{$payment->realize_date}}</span>
                            @endif
                        </td>
                        <td>
                            @currency($payment->payment_amount)
                        </td>
                        <td>
                            <a class="btn btn-xs btn-primary"
                               href="{{ route('admin.payment.show', $payment->id) }}">
                                {{ trans('global.view') }}
                            </a>
                            @if((($payment->payment_type=='cheque' && $payment->status != 'reject') && strtotime($payment->realize_date)<strtotime('now')))
                                {{--Cheque Return--}}
                                <button class="btn btn-xs btn-warning" id="show-modal" @click="showModal = {{$payment->id}}">Cheque Return</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $payments->appends($_GET)->links() }}
        @else
         <empty-results message="{{trans('global.no_entries_in_table')}}"></empty-results> 
        @endif
    </div>
</div>
    <modal v-if="showModal">
        <template v-slot:header>
            Cheque Return
        </template>
        <template v-slot:body>
            <form :action="'{{config('app.url')}}/admin/payment/'+showModal" method="post" id="form-return" onsubmit="confirmSubmit(this);return false;">
                <label>Adjustment charge</label>
                <input step="0.01" class="form-control" name="cheque_return_charge" value="0">
                <input type="hidden" name="cheque_return" value="true">
                @method('PUT')
                @csrf
            </form>
        </template>
        <template v-slot:footer>
            <button class="btn btn-default" @click="showModal = false">
                Close
            </button>
            <button class="btn btn-primary" form="form-return">Submit</button>
        </template>
    </modal>
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

@section('nav-item')
    @component('partials.navitem')
        @slot('links')
            @can('users_manage')
                <a class="nav-item nav-link" href="{{route('admin.payment.create')}}"><i class="fa fa-plus"></i>
                    New Payment</a>
            @endcan
        @endslot
    @endcomponent
@endsection