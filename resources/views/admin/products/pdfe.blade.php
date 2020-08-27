@inject('ProductTrait', 'App\Http\Controllers\Admin\ProductsController')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode</title>
    <style>
        body {
            height: 842px;
            width: 595px;
            /* to centre page on screen*/
            margin-left: auto;
            margin-right: auto;
        }
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
                $url = DNS2D::getBarcodePNGPath($code, "QRCODE",4,4);
            @endphp
            <td style="border: solid 1px black; margin:10px;padding:5px">
            <img style="display:block; padding: 0px;margin: 0px;" src="{!! asset(trim($url,"\\")) !!}" >
            <p style="padding: 0px;margin:0px;font-size: 9px;">{{$code}}</p>
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