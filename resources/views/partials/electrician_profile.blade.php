<div class="card">
    <div class="card-header bg-primary">
        <a href="{{route('admin.electrician.show',$electrician)}}" class="btn btn-xs btn-danger"><i class="fa fa-user"></i></a> {{trans('global.ele_profile')}}
    </div>
    <div class="card-body">
        @if($electrician->photo!='')
            <div class="text-center">
                <img class="img img-thumbnail img-thumb-profile mb-2 img-center" style="max-height: 200px;" src="{{ asset('images/electrician/'.$electrician->photo)}}" />
            </div>
        @endif
        <h3 class="card-title text-center">{{$electrician->name}}</h3>
        <table class="table table-borderless">
            <tbody>
            <tr>
                <td>NIC</td>
                <td>{{$electrician->nic}}</td>
            </tr>
            <tr>
                <td>Telephone</td>
                <td>{{Helper::sanitizeLkTelephone($electrician->telephone)}}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td>
                    {{$electrician->block}},
                    {{$electrician->street}},
                    {{$electrician->city}},
                    {{$electrician->province}}
                </td>
            </tr>
            </tbody>
        </table>
        <div class="text-center">Total <strong>{{$electrician->points}}</strong> Points |

        @if($electrician->float_points)
            <a href="{{route('admin.payments.create',['electrician'=>$electrician->id])}}">Payable <strong>({{$electrician->float_points}})</strong></a>
            @else
                Payable <strong>({{$electrician->float_points}})</strong>
            @endif
        </div>
    </div>
</div>
