<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <title>Ezren</title>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            color: #001028;
            padding: 0 20px;
            width: 14.8cm;
            height: 21cm;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-family: Arial;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url({{ asset('assets/front-end/img/dimension.png') }});
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        .table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        .table .service,
        .table .desc {
            text-align: left;
        }

        .table td {
            padding: 20px;
            text-align: right;
        }

        .table td.service,
        .table td.desc {
            vertical-align: top;
        }

        .table td.unit,
        .table td.qty,
        .table td.total {
            font-size: 1.2em;
        }

        .table td.grand {
            border-top: 1px solid #5D6975;
            ;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }

    </style>
</head>

<body>
    @php
        use App\Model\BusinessSetting;
        $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;
        $company_mobile_logo =BusinessSetting::where('type', 'company_mobile_logo')->first()->value;
        $address =BusinessSetting::where('type', 'address')->first()->value;
        $address = json_decode($address);
        $district= $address->district;
        $city = $address->city;
        $province = $address->province;
        $cus = json_decode($order['shipping_address_data']);
    @endphp
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('storage/company'.'/'.$company_web_logo) }}">
        </div>
        <h1>INVOICE {{ $order['id'] }}</h1>
        <div id="company" class="clearfix" style="padding-right: 20px">
            <div>{{ $company_name }},<br /> {{ $district }}, {{ $city }}, {{ $province }}, ID</div>
            <div>{{ $company_phone }}</div>
            <div><a href="mailto:company@example.com">{{ $company_email }}</a></div>
        </div>
        <div id="project" style="padding-left: 20px">
            <div><span>PENERIMA</span> :{{ $cus->contact_person_name }}</div>
            <div><span>ALAMAT</span> :{{ $cus->district }}, {{ $cus->city }}, {{ $cus->province }}, ID</div>
            <div><span>HANDPHONE</span> :{{ $cus->phone }}</div>
            <div><span>TANGGAL</span> :{{date('d-m-Y h:i:s a',strtotime($order['created_at']))}}</div>
        </div>
    </header>
    <main>
        <table class="table" style="padding: 0 20px;">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th class="service">PRODUK</th>
                    <th class="desc">HARGA SATUAN</th>
                    <th>JUMLAH</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            @php
                $subtotal=0;
                $total=0;
                $sub_total=0;
                $total_tax=0;
                $total_shipping_cost=0;
                $total_discount_on_product=0;
            @endphp
            <tbody>
                @foreach($order->details as $key=>$details)
                @php $subtotal=($details['price'])*$details->qty
                @endphp
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td class="service">{{$details['product']?$details['product']->name:''}}</td>
                    <td class="price">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($details['price']))}}</td>
                    <td class="unit">{{$details->qty}}</td>
                    <td class="qty">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                </tr>
                @php
                    $sub_total+=$details['price']*$details['qty'];
                    $total_tax+=$details['tax'];
                    $total_shipping_cost+=$details->shipping ? $details->shipping->cost :0;
                    $total_discount_on_product+=$details['discount'];
                    $total+=$subtotal;
                    @endphp
            @endforeach
            </tbody>
        </table>
        @php($shipping=$order['shipping_cost'])
        <table style="padding-right: 20px;">
            <thead>
                <tr>
                    <th style="width:200px;"></th>
                    <th style="width:200px;"></th>
                    <th style="width:100px;"></th>
                    <th style="width:200px;"></th>
                    <th></th>
                </tr>
            </thead>
            <tr>
                <td colspan="3"></td>
                <td>Subtotal</td>
                <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($sub_total))}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Pajak</td>
                <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax))}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Ongkos Kirim</td>
                <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Diskon Kupon</td>
                <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->discount_amount))}}</td>
            </tr>
            <tr style="border-bottom: 1px solid #000;">
                <td></td>
                <td></td>
                <td></td>
                <td>Diskon produk</td>
                <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_discount_on_product))}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: 700; font-size:20px;">Total</td>
                <td style="font-weight: 700; font-size:20px; color:green; background-color:#e3e3e3;">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}</td>
            </tr>
        </table>
        {{-- <div id="notices">
            <div>NOTICE:</div>
            <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
        </div> --}}
    </main>
    <footer>
        Invoice ini dicetak melalui komputer, dan merupakan bukti transaksi yang sah.
    </footer>
</body>

</html>
