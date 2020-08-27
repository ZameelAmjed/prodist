@extends('layouts.admin')
@section('content')
@include('partials.breadcrumb',['links'=>[
['name'=>'Electrician','url'=>route('admin.electrician.index')],
['name'=>'Profile','url'=>route('admin.electrician.show',$electrician)]
],
'pageimage'=>'handyman.svg'])
@component('partials.backbutton')
    @slot('more')
        @can('users_manage')
        @if($electrician->float_points)
            <a class="btn btn-success" href="{{ route("admin.payments.create", compact('electrician')) }}">
                {{ trans('global.send') }} {{ trans('cruds.payments.title_singular') }}
            </a>
        @endif
    @endcan
    @endslot
    @endcomponent

<div class="card">
    <div class="card-header">
        {{ trans('cruds.electrician.title_singular') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <div class="row">
                <div class="col-md-4">
                    @include('partials.electrician_profile',compact('electrician'))
                    <a class="btn btn-xs btn-info" href="{{ route('admin.electrician.edit', $electrician->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                    @cannot('sales_executive')
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
                </div>
                <div class="col-md-8">
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#attributes" role="tab">More Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Reward Payments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#reward" role="tab">Rewards</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bank" role="tab">Bank Info</a>
                            </li>
                        </ul>

                        <!-- Tab panes {Fade}  -->
                        <div class="tab-content">
                            <div class="tab-pane in active" id="attributes" name="attributes" role="tabpanel">
                                <table class="table table-condensed">
                                    <tbody>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.electrician.fields.id') }}
                                        </th>
                                        <td>
                                            {{ $electrician->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.electrician.fields.code') }}
                                        </th>
                                        <td>
                                            {{ $electrician->member_code }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.electrician.fields.dob') }}
                                        </th>
                                        <td>
                                            {{$electrician->date_of_birth}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.electrician.fields.celebration') }}
                                        </th>
                                        <td>
                                            {{$electrician->celebration}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.electrician.fields.created_at') }}
                                        </th>
                                        <td>
                                            {{$electrician->created_at->format('d/m/Y')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.electrician.fields.language') }}
                                        </th>
                                        <td>
                                            {{$electrician->language}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="gap">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.dealer.title_singular') }}
                                        </th>
                                        <td>
                                            {{$electrician->dealer->name??''}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('global.region') }}
                                        </th>
                                        <td>
                                            {{$electrician->region}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('global.area') }}
                                        </th>
                                        <td>
                                            {{$electrician->area}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="gap"></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.electrician.fields.status') }}
                                        </th>
                                        <td>
                                            {!! trans('global.label_'.$electrician->status) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Status Update By
                                        </th>
                                        <td>
                                            {{$electrician->approvedBy->name ?? ''}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel">
                                @if(count($electrician->payments))
                                <table class="table table-condensed">
                                    <thead>
                                    <th>Points/Amount</th>
                                    <th>Transfer Type</th>
                                    <th>Payed On</th>
                                    <th>Comment</th>
                                    </thead>
                                @foreach($electrician->payments as $key => $payment)
                                    <tr>
                                        <td>{{$payment->points}}</td>
                                        <td>{{$payment->transfer_type}}</td>
                                        <td>{{$payment->payed_on}}</td>
                                        <td>{{$payment->comment}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                    @else
                                <p class="alert alert-info">{{trans('global.nothing_found',['table'=>'payments'])}}</p>
                                    @endif
                            </div>
                            <div class="tab-pane fade" id="reward" role="tabpanel">
                                @if(count($electrician->getRewards()))
                                    <table class="table table-condensed">
                                    <thead>
                                    <th>Barcode</th>
                                    <th>Product Model</th>
                                    <th>Points</th>
                                    <th>Date</th>
                                    </thead>
                                    @foreach($electrician->getRewards() as $key => $reward)
                                        <tr>
                                            <td>{{$reward->barcode}}</td>
                                            <td>{{$reward->model}}</td>
                                            <td>{{$reward->points}}</td>
                                            <td>{{$reward->created_at}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                @else
                                    <p class="alert alert-info">{{trans('global.nothing_found',['table'=>'rewards'])}}</p>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="bank" role="tabpanel">
                                <p>
                                {{$electrician->bank_account_no}}<br>
                                {{$electrician->bank_name}}<br>
                                {{$electrician->bank_city}}<br>
                                {{$electrician->bank_code}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             </div>
    </div>
</div>



@endsection