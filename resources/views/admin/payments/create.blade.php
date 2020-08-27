@extends('layouts.admin')
@include('partials.backbutton',['back'=>route('admin.electrician.show',$electrician)])
@include('partials.breadcrumb',['links'=>[
['name'=>'Payment Requests','url'=>route('admin.payments.requests')],
['name'=>'Make','url'=>route('admin.payments.create')]
],'pageimage'=>'money.svg'])
@section('content')
    @php
        $pendingPayment = (@$electrician->paymentRequest->first()->status == 'pending');
    @endphp
    @if($pendingPayment)
        <div class="alert alert-danger">
            <p>
                Payment Request {{str_pad($electrician->paymentRequest->first()->id,5,0,STR_PAD_LEFT)}} created on {{$electrician->paymentRequest->first()->created_at}} is still pending. <a href="{{route('admin.payments.generate',['id'=>$electrician->id,'payable_points'=>$electrician->paymentRequest->first()->amount])}}">download here</a>
            </p>
        </div>
        @else
        <div class="alert alert-info">
            <p>
                Electrician have no payment requests made. Firstly generate a payment request from below to edit this from
            </p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            {{ trans('global.make') }} {{ trans('cruds.payments.title_singular') }}
        </div>

        <div class="card-body">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="{{ route("admin.payments.store") }}" method="POST" enctype="multipart/form-data">
                            <fieldset {{($pendingPayment)?'':'disabled="disabled"'}}>
                            @csrf()
                            <input type="hidden" value="{{$electrician->id}}" name="electrician_id">
                            <div class="form-group {{ $errors->has('points') ? 'has-error' : '' }}">
                                <label for="points">{{ trans('cruds.payments.fields.points') }}*</label>
                                <input step="0.00" type="number" max="{{@$electrician->paymentRequest->first()->amount}}" id="points"
                                       name="points" class="form-control"
                                       value="{{ old('points', ($pendingPayment) ? $electrician->paymentRequest->first()->amount : 0) }}"
                                       required>
                                @if($errors->has('points'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('points') }}
                                    </em>
                                @endif
                                <p class="helper-block {{($electrician->float_points==0)?'hide':''}}">
                                    Total {{$electrician->float_points}} points available but maximum is up to the value in payment request.
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('transfer_type') ? 'has-error' : '' }}">
                                <label for="transfer_type">{{ trans('cruds.payments.fields.transfer_type') }}*</label>
                                <select id="transfer_type"
                                        name="transfer_type"
                                        class="form-control">
                                    <option value="Bank Deposit">Bank Deposit</option>
                                    <option value="Direct Pay">Direct Pay</option>
                                    <option value="Draft">Draft</option>
                                </select>
                                @if($errors->has('transfer_type'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('transfer_type') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.payments.fields.name_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('payed_on') ? 'has-error' : '' }}">
                                <label for="payed_on">{{ trans('cruds.payments.fields.payed_on') }}</label>
                                <input type="date" id="payed_on" name="payed_on" class="form-control"
                                       value="{{date('Y-m-d')}}">
                                @if($errors->has('payed_on'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('payed_on') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.payments.fields.name_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
                                <label for="comment">{{ trans('cruds.payments.fields.comment') }}*</label>
                                <input type="text" id="comment" name="comment" class="form-control"
                                       value="{{ old('comment', isset($electrician) ? $electrician->comment : '') }}">
                                @if($errors->has('comment'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('comment') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.payments.fields.name_helper') }}
                                </p>
                            </div>
                            <div>
                                <input class="btn btn-danger {{($pendingPayment)?'':'disabled'}}" type="submit" value="{{ trans('global.save') }}">
                            </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="col-md-4">
                        @include('partials.electrician_profile',compact('electrician'))
                        <div class="card">
                            <div class="card-header bg-danger">
                                {{trans('global.gen_payment_doc')}}
                            </div>
                            <div class="card-body">
                                <form method="get" action="{{route('admin.payments.generate')}}">
                                    <div class="form-inline">
                                        <div class="form-group {{ $errors->has('payable_points') ? 'has-error' : '' }}">
                                            <input value="{{old('payable_points')}}" placeholder="enter amount"
                                                   step="0.00" type="number" max="{{$electrician->float_points}}"
                                                   id="points" name="payable_points" class="form-control" required>
                                            <input type="hidden" value="{{$electrician->id}}" name="id">
                                            <button class="btn btn-md btn-primary">{{trans('global.generate')}}</button>
                                            @if($errors->has('payable_points'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('payable_points') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                @if($pendingPayment)
                                    <p class="text-danger">
                                        <em>This will regenerate payment request</em>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection