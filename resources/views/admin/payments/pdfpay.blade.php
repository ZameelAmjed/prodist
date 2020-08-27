@php
    $now = Carbon\Carbon::now();
@endphp
<!DOCTYPE html>
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <meta charset="utf-8">
    <title>{{config('app.name')}} Pay for Rewards Request</title>
    <style>
        body {
            /*height: 842px;
            width: 595px;*/
            /* to centre page on screen*/
            margin-left: auto;
            margin-right: auto;
            font-size: 11px;
        }
        td{
            padding-bottom: 0;
            padding-top: 0;
        }
    </style>
</head>
<body>
<h6 class="text-center">{{config('app.name')}}</h6>
<h5 class="text-center">Request for Rewards Payment</h5>
<table class="table table-sm table-borderless w-auto p-0 m-0">
    <tr>
        <td>Request ID:</td>
        <td>{{$attr['uid']}}</td>
    </tr>
    <tr>
        <td>Date:</td>
        <td>{{$now->toDateString()}}</td>
    </tr>
</table>
<table class="table table-sm table-bordered p-0 m-0">
    <thead>
    <tr class="table-secondary">
        <td colspan="2" class="text-center text-bold">
            Payee Information
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <table class="table table-sm table-borderless w-auto m-0 p-0">
                <tbody>
                <tr>
                    <td>Name</td>
                    <td>{{$electrician->name}}</td>
                </tr>
                <tr>
                    <td>NIC</td>
                    <td>{{$electrician->nic}}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{$electrician->block}},{{$electrician->street}},{{$electrician->city}}</td>
                </tr>
                <tr>
                    <td>Telephone</td>
                    <td>{{$electrician->phone}}</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td>
            <table class="table table-sm table-borderless w-auto p-0 m-0">
                <tbody>
                <tr>
                    <td>Account No</td>
                    <td>{{$electrician->bank_account_no}}</td>
                </tr>
                <tr>
                    <td>Bank Name</td>
                    <td>{{$electrician->bank_name}}</td>
                </tr>
                <tr>
                    <td>Branch</td>
                    <td>{{$electrician->bank_city}}</td>
                </tr>
                <tr>
                    <td>Bank Code</td>
                    <td>{{$electrician->bank_code}}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<p class="text-center">I hereby certify that the recipient has earned points equellent to <strong>RS.{{number_format($attr['payable_points'],2)}}</strong> from chint rewards program and request to process the payment of same amount to above payee.</p>
<table class="table table-sm table-borderless w-auto p-0 m-0">
    <tbody>
    <tr>
        <td>Amount</td>
        <td>: Rs.{{number_format($attr['payable_points'],2)}}/=</td>
    </tr>
    <tr>
        <td>Signature</td>
        <td>:______________</td>
    </tr>
    </tbody>
</table>
<table class="table table-sm table-borderless">
    <thead>
    <tr class="table-secondary">
        <td colspan="2" class="text-center">
            Payment Status (To be filled by Accounts Dept.)
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>Processed Date</td>
                    <td>:______________</td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>:______________</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Authorized Signature</td>
                    <td>:______________</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>