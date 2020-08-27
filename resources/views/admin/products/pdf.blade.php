@inject('ProductTrait', 'App\Http\Controllers\Admin\ProductsController')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode</title>
    <style type="text/css" rel="stylesheet">

    </style>
</head>
<body>
@php
    $i = intval($attr['start']);
    $j = intval($attr['end']);
@endphp
<table class="">
<tbody>
@for(;$i<$j;)
    <tr>
        @for($q=0;$q<6;$q++)

            @php
            if(($i+$q)>$j)
            return;
                $code = $ProductTrait->codePrinter($product->textcode,$i+$q) ;
            @endphp
            <td>
            <img style="display:block; padding: 0;margin: 0px;" src="{!!  URL::asset(DNS2D::getBarcodePNGPath($code, "QRCODE",4,4)) !!}" >
            <p>{{$code}}</p>
            </td>
        @endfor
    </tr>
    @php
    $i = $i+6;
    @endphp
@endfor
</tbody>
</table>
</body>
</html>