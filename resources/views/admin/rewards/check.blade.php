@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Check Rewards','url'=>route('admin.rewards.check')],
],
'pageimage'=>'tick.svg'])
@section('content')
<div class="card">
    <div class="card-header">
        {{trans('global.check_rewards')}}
    </div>
    <div class="card-body">
        <form class="form-inline">
            <input type="text" id="barcode" name="barcode"
                   placeholder="{{trans('global.enter_barcode')}}"
                   class="form-control mr-1 col-md-5"
                   value="{{old('barcode',request()->get('barcode'))}}"
                   required>
            <button class="btn btn-primary" type="submit">Check</button>
            @if($errors->has('barcode'))
                <em class="invalid-feedback ">
                    {{ $errors->first('barcode') }}
                </em>
            @endif
        </form>

        @if($barcode)
        <div class="mt-5"></div>
        <div class="alert {{($barcode['status'])?'bg-success':'bg-danger'}}">
           {{(!$barcode['status'])?$barcode['message']:''}}
            @if(isset($barcode['rewardData']))
                <a href="{{route('admin.electrician.show', json_decode($barcode['rewardData'][0])->electrician)}}">More Info</a>
                <p>Click below, if you wish to remove this product from electrician and reduce points.</p>
                <form action="{{route('admin.rewards.destroy',1)}}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="barcode" value="{{ request()->get('barcode') }}">
                    <button class="btn btn-warning">Remove</button>
                </form>
            @endif
           @if(isset($barcode['data']))
                <strong>{{$barcode['data']['product_name']}}</strong> - Barcode is valid for {{$barcode['data']['points']}} points
            @endif
        </div>
        @endif

    </div>
</div>
@endsection