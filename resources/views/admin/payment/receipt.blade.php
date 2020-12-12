<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
        .text-danger{
            color: red;
        }
    </style>

</head>
<body>

<table width="100%">
    <tr>
        <td valign="top"><img src="{{asset('images/logo.png')}}" alt="" width="150"/></td>
        <td align="right">
            <h3>{{config('app.company_name')}}</h3>
            <pre>
                {{config('app.company_address_block')}}, {{config('app.company_address_street')}}, {{config('app.company_address_city')}}
                {{config('app.company_tax_id')}}
                {{config('app.company_telephone')}}
                {{config('app.company_email')}}
            </pre>
        </td>
    </tr>

</table>
<h3>RECEIPT</h3>
@if($payment->status != 'accept')
    <h5 class="text-danger">
	    {{($payment->status==\App\Payment::return)?trans('global.payments_returned'):''}}
	    {{($payment->status==\App\Payment::reject)?trans('global.cheque_dishonored'):''}}
    </h5>
    <small><em>{{$payment->comment}}</em></small>
@endif
<table width="100%">
    <tr>
        <td>
            <strong>Date: </strong>{{$payment->created_at}}
        </td>
        <td align="right">
            <strong>Receipt No: </strong>{{$payment->uid}}
        </td>
    </tr>
    <tr>
        <td>
            <strong>Payee: </strong>{{$payment->order->store->business_name}}, {{$payment->order->store->business_name}}
        </td>
        <td align="right">
            <strong>Invoice No: </strong>{{$payment->order->invoiceUid}}
        </td>
    </tr>
    <tr>
        <td>
            <strong>Amount Received: </strong>@currency($payment->payment_amount)
        </td>
        <td align="right">
            <strong>Payment Method: </strong>{{$payment->payment_type}}
        </td>
    </tr>
    @if($payment->payment_type=='cheque')
        <tr>
            <td>
                <strong>Cheque No: </strong>{{$payment->cheque_no}}
            </td>
            <td align="right">
                <strong>Realize Date: </strong>{{$payment->realize_date}}
            </td>
        </tr>
    @endif
</table>

<br/>
<table width="100%">
    <thead style="background-color: lightgray;">
    </thead>
    <tbody>
    @php
        $totalSave = 0
    @endphp
    @foreach($payment->order->products as $key=>$product)
        @php
            $discount = (($product->item->unit_price*$product->item->qty)*($product->item->discount/100));
            $totalSave += $discount;
        @endphp
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <td align="left">Subtotal</td>
        <td align="right">@currency($payment->order->subtotal_amount)</td>
    </tr>
    <tr>
        <td align="left">Total Discounts</td>
        <td align="right">@currency($totalSave+$payment->order->subtotal_amount*($payment->order->discount/100))</td>
    </tr>
    <tr>
        <td align="left" class="gray">Total Order Amount</td>
        <td align="right" class="gray">@currency($payment->order->total_amount)</td>
    </tr>
    <tr>
        <td align="left" class="gray">
            @if($payment->status != \App\Payment::accept)
                <del>Amount Paid</del>
            @else
                Amount Paid
            @endif
        </td>
        <td align="right" class="gray">
            @if($payment->status != \App\Payment::accept)
                <del>@currency($payment->payment_amount)</del>
            @else
                @currency($payment->payment_amount)
            @endif
        </td>
    </tr>
    @php
       $totalPaymentsMade = $payment->order->payments->where('status','accept')->sum('payment_amount');

    @endphp
    <tr>
        <td align="left" class="">Total Payments Made</td>
        <td align="right" class="">@currency($totalPaymentsMade)</td>
    </tr>
    <tr>
        <td align="left" class="">Remaining Payment</td>
        <td align="right" class="">@currency($payment->order->total_amount-$totalPaymentsMade)</td>
    </tr>
    </tfoot>
</table>
</body>
</html>