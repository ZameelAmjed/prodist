@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="card-body p-0 bg-white shadow-sm">
                <div class="text-white text-right bg-blue p-4">
                    <div class="media">
                        <img src="{{asset('images/store.svg')}}" height="70px">
                        <div class="media-body">
                            <h2 class="mt-0"><a href="{{route('admin.stores.index')}}" class="no-decoration">{{$count->totalStores}}</a></h2>
                        </div>
                    </div>
                </div>
                <h3 class="p-0 m-0 text-muted text-uppercase font-weight-bold pl-2">
                    Stores
                </h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-body p-0 bg-white shadow-sm">
                <div class="text-white text-right bg-yellow p-4">
                    <div class="media">
                        <img src="{{asset('images/boxes.svg')}}" height="70px">
                        <div class="media-body">
                            <h2 class="mt-0"><a href="{{route('admin.reports.inventory')}}" class="no-decoration">{{$count->totalInventory}}</a></h2>
                        </div>
                    </div>
                </div>
                <h3 class="p-0 m-0 text-muted text-uppercase font-weight-bold pl-2">
                    Inventory
                </h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-body p-0 bg-white shadow-sm">
                <div class="text-white text-right bg-green p-4">
                    <div class="media">
                        <img src="{{asset('images/money.svg')}}" height="70px">
                        <div class="media-body">
                            <h2 class="mt-0"><a href="{{route('admin.orders.index',['status'=>'processing'])}}" class="no-decoration">{{$count->totalPendingOrders}}</a></h2>
                        </div>
                    </div>
                </div>
                <h3 class="p-0 m-0 text-muted text-uppercase font-weight-bold pl-2">
                    Orders Due Payment
                </h3>
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
        data: {{json_encode($count->orders)}},
        backgroundColor: '#E55353',
        label: 'Total Orders',
      },
      {
        data: {{json_encode($count->payments)}},
        backgroundColor: '#4dbd74',
        label: 'Recived Payments',
      },
      {
        data: {{json_encode($count->supplierOrders)}},
        backgroundColor: '#ffc107',
        label: 'Supplier Orders',
      }
    ]"
                        labels="months"
                        :options="{ maintainAspectRatio: false }"
                />

        </div>
        <div class="col-md-6">
            <payments-home-tabs></payments-home-tabs>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">

        </div>
    </div>
    @endcannot
</div>

@endsection
@section('scripts')
@parent

@endsection