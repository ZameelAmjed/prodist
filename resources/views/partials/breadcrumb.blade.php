@section('breadcrumb')
    @if(isset($pageimage))
        <img class="breadcumb-top-img" src="{{asset('images/'.$pageimage)}}">
    @endif
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($links as $link)
            @if(request()->is($link['url'])||(request()->url()==$link['url']))
                <li class="breadcrumb-item active" aria-current="page">{{$link['name']}}</li>
            @else
                <li class="breadcrumb-item"><a href="{{$link['url']}}">{{$link['name']}}</a></li>
            @endif
            @endforeach
    </ol>
</nav>
@endsection
