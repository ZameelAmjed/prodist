@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Rewards','url'=>route('admin.rewards.index')],
['name'=>'Bulk','url'=>route('admin.rewards.bulk',['id'=>$electrician->id])],
]])
@section('content')
@include('partials.backbutton')
@can('users_manage')
    <div class="alert alert-danger">
        <p>Use this option for products issued without tracking barcode in case a product with barcode has been added same can be reclaimed by electrician for points</p>
    </div>
    <div class="card">
        <div class="card-header">
            Add Rewards [Bulk]
        </div>
        <div class="card-body">
            @if(isset($electrician))
                <div class="row">
                    <div class="col-md-8">
                        <bulk-rewards-pusher :electrician="{{$electrician->id}}"></bulk-rewards-pusher>
                    </div>
                    <div class="col-md-4">
                        @include('partials.electrician_profile',compact($electrician) )
                    </div>
                </div>
            @endif
        </div>
    </div>

@endcan
@endsection