@section('content-buttons')
<div class="row pb-5">
    <div class="col-lg-12">
        <a class="btn btn-default" href="{{isset($back)?$back:url()->previous()}}">
            <i class="fa fa-arrow-left"></i> {{trans('global.back')}}
        </a>
        {{$more??''}}
    </div>
</div>
@endsection