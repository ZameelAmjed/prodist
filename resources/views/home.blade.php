@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="card-body p-0 bg-white shadow-sm">
                <div class="text-white text-right bg-blue p-4">
                   {{-- <i class="fa fa-bolt fa-4x"></i>--}}
                    <img src="{{asset('images/handyman.svg')}}" height="70px">
                </div>
                <div class="text-value-lg pl-2 text">
                    {{$count->electrician ?? 0}}
                </div>
                <small class="text-muted text-uppercase font-weight-bold pl-2">
                    Electricians
                </small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-body p-0 bg-white shadow-sm">
                <div class="text-white text-right bg-yellow p-4">
                    <img src="{{asset('images/boxes.svg')}}" height="70px">
                </div>
                <div class="text-value-lg pl-2 text">
                    {{$count->products ?? 0}}
                </div>
                <small class="text-muted text-uppercase font-weight-bold pl-2">
                    Products
                </small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-body p-0 bg-white shadow-sm">
                <div class="text-white text-right bg-green p-4">
                    <img src="{{asset('images/mover-truck.svg')}}" height="70px">
                </div>
                <div class="text-value-lg pl-2 text">
                    {{$count->dealers?? 0}}
                </div>
                <small class="text-muted text-uppercase font-weight-bold pl-2">
                    Dealers
                </small>
            </div>
        </div>
    </div>
    @cannot('sales_executive')
    <div class="row mt-5">
        <div class="col-md-6">
                <c-chart-bar
                        style="height:300px"
                        :datasets="[
      {
        data: {{json_encode($count->monthlyPayment)}},
        backgroundColor: '#E55353',
        label: 'Payments',
      }
    ]"
                        labels="months"
                        :options="{ maintainAspectRatio: false }"
                />

        </div>
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" colspan="4">Top rewards to be payed</th>
                </tr>
                </thead>
                @foreach($pending_payments as $key=>$pending_payment)
                    <tbody>
                    <tr>
                        <th scope="row">{{$key+1}}</th>
                        <td>{{$pending_payment->name}}</td>
                        <td></td>
                        <td><a href="{{route('admin.payments.create',['electrician'=>$pending_payment->id])}}">{{$pending_payment->float_points}} points</a></td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" colspan="4">Recent rewards</th>
                </tr>
                </thead>
                @foreach($rewards as $key=>$reward)
                    <tbody>
                    <tr>
                        <th scope="row">{{$key+1}}</th>
                        <td>{{$reward->electricianInfo->name}}</td>
                        <td><a href="{{route('admin.rewards.check',['barcode'=>$reward->barcode])}}">{{$reward->barcode}}</a></td>
                        <td>{{$reward->created_at}}</td>
                        <td><a href="{{route('admin.payments.create',['electrician'=>$reward->electrician])}}">{{$reward->points}} points</a></td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
    @endcannot
<!-- Info message -->
    {{--<a class="btn btn-info" onclick="toastr.info('Hi! I am info message.');">Info message</a>
--}}
</div>

@endsection
@section('scripts')
@parent

@endsection