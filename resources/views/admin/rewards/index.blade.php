@extends('layouts.admin')
@include('partials.breadcrumb',['links'=>[
['name'=>'Add Rewards','url'=>route('admin.rewards.index')]
],'pageimage'=>'barcode.svg'])
@section('content')
@can('users_manage')
    <div class="card">
        <div class="card-header">
            Add Rewards
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" id="nic" name="nic"
                               placeholder="{{trans('cruds.electrician.title')}} {{ trans('cruds.electrician.fields.nic') }} or {{ trans('cruds.electrician.fields.telephone') }}"
                               class="form-control mr-1 col-md-5"
                               value="{{old('nic',$electrician->telephone ?? '')}}"
                               required>
                        <button class="btn btn-primary" type="submit">Find</button>
                                @if($errors->has('nic'))
                                        <em class="invalid-feedback ">
                                            {{ $errors->first('nic') }}
                                        </em>
                                    @endif
                     </form>
                </div>
            </div>
            @if(isset($electrician))
            <div class="row">
              <div class="col-md-8">
                  <rewards-pusher :electrician="{{$electrician->id}}"></rewards-pusher>
              </div>
              <div class="col-md-4">
                      @include('partials.electrician_profile',compact($electrician) )
              </div>
            </div>
                <a href="{{route('admin.rewards.bulk',['id'=>$electrician->id])}}">Add Multiple Products</a>
            @endif
        </div>
    </div>

@endcan

@endsection
@section('scripts')

@parent

@endsection