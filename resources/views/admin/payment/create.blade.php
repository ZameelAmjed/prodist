@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Payments','url'=>route('admin.payment.index')],
['name'=>'Create','url'=>route('admin.payment.create')],
],
'pageimage'=>'money.svg'])
@section('content')
@component('partials.backbutton')
@endcomponent
<div class="card">
    <div class="card-header">
       {{ trans('global.add') }} {{ trans('cruds.payments.title_singular') }}
    </div>
    <div class="card-body">
        <bulk-payment :banks="{{json_encode(trans('global.banks'))}}"></bulk-payment>
    </div>
</div>
@endsection

