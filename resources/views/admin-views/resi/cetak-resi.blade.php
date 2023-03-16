<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Cetak Resi</title>
  <style>
    .container {
      max-width: 100vw;
      text-align: center;
    }

    .row .child-row {
      max-width: 900px;
      padding: 0;
      margin: 20px;
      border: 2px solid #787878;
      border-radius: 3px;
    }

    .header {
      border-bottom: 2px solid #787878;
      padding: 10px;
    }

    .shipping img {
      border: 2px solid #000;
      border-radius: 10px;
    }

    .shipping-label,
    .barcode-box,
    .address,
    .footer {
      border-bottom: 2px solid #787878;
      padding: 10px;
    }

    .jne-logo {
      border-right: 1px solid #787878;
    }

    .address .col-8,
    .footer .col-6,
    .footer .col-3 {
      border-right: 1px solid #787878;
    }

    th small, tr td{
      font-size: 10px;
    }

  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
    integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  {{-- {{ dd($order) }} --}}
  <div class="container">
    <div class="row">
      <div class="child-row">
        <div class="header d-flex justify-content-between">
          <div class="company d-flex">
            <img src="{{ asset('storage/company'.'/'.$web_config['web_logo']['value']) }}" class="me-3" height="35px"
              alt="">
            <div class="d-flex flex-column align-items-start">
              <div class="cs">
                <i class="fa-solid fa-headset"></i> {{ $web_config['phone']['value'] }}
              </div>
              <div class="email">
                <i class="fa-solid fa-envelope"></i> {{ $web_config['email']['value'] }}
              </div>
            </div>
          </div>
          <div class="shipping d-flex">
            <img src="{{ asset('assets/front-end/img/umbrella.png') }}" alt="" class="me-2" height="50px">
            <img src="{{ asset('assets/front-end/img/fragile.png') }}" class="p-1 me-2" alt="" height="50px">
            <img src="{{ asset('assets/front-end/img/hand.png') }}" alt="" height="50px">
          </div>
        </div>
        <div class="shipping-label d-flex justify-content-between">
          <span class="text-bold fw-bold fs-6">Shipping label</span>
          <span>Waktu Order: {{ $order['updated_at'] }}</span>
          <span>EZR{{ $order['id'] }}</span>
        </div>
        <div class="barcode-box d-flex p-0">
          <div class="jne-logo p-2 px-5 d-flex flex-column col-4">
            <img src="{{ asset('assets/front-end/img/jne.png') }}" alt="" height="50px">
            <span class="mt-2">JNE Cashless</span>
          </div>
          <div class="barcode d-flex justify-content-center align-items-center col-8 flex-column">
            @php
            $height = '50px';
            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
            @endphp
            {!! $generator->getBarcode($order['no_resi'], $generator::TYPE_CODE_128, 2, 50) !!}
            <span class="d-block mt-2">Airway Bill: {{ $order['no_resi'] }}</span>
          </div>
        </div>
        <div class="address p-0">
          <div class="row">
            <div class="col-8 p-2 text-start">
              <div class="row">
                <div class="col-2">
                  <span class="title ms-4 fw-bold">
                    Kepada
                  </span>
                </div>
                <div class="col-10">
                  <span class="fw-bold">: address user</span>
                </div>
              </div>
            </div>
            <div class="col-4 p-2 text-start">
              <span class="title ms-2 fw-bold">
                Shipping note :
              </span>
              <span class="d-block ms-2">
                note
              </span>
            </div>
          </div>
        </div>
        <div class="footer p-0">
          <div class="row">
            <div class="col-6 p-2">
              <span class="fw-bold fs-1">CASHLESS</span>
              <span class="fw-bold fs-6 d-block">Tidak perlu membayar apapun ke logistik</span>
            </div>
            <div class="col-3 p-2 d-flex flex-column justify-content-center">
              @php
                $jne = json_decode($web_config['jne']['value'])->origin;
              @endphp
              <span class="text-uppercase d-block fs-3">{{ $jne }}</span>
              <span class="fw-bold text-capitalize mt-2">origin code</span>
            </div>
            <div class="col-3 p-2 d-flex flex-column justify-content-center" style="border-right: unset;">
              <span class="text-uppercase d-block fs-3">{{ $order['destination_code'] ?? 'EMPTY' }}</span>
              <span class="fw-bold text-capitalize mt-2">destination code</span>
            </div>
          </div>
        </div>
        <div class="foot">
          <div class="cut-label d-block text-start ms-2 text-danger position-relative">
            <i class="fa-solid fa-scissors position-absolute" style="margin-top: -7px;"></i>
            <small class="ms-4">Lipat, sembunyikan atau pishkan bagian ini saat pengiriman</small>
          </div>
          <div class="produk-list text-start p-2 px-4">
            <span class="fw-bold">Produk dalam paket ini</span>
            <table class="table mt-2" style="border: 2px solid #000; border-radius: 4px;">
              <thead>
                <tr>
                  <th scope="col" style="padding-top: 0 !important; padding-bottom: 0 !important;"><small>Order item
                      ID</small></th>
                  <th scope="col" style="padding-top: 0 !important; padding-bottom: 0 !important;"><small>Nama
                      Produk</small></th>
                  <th scope="col" style="padding-top: 0 !important; padding-bottom: 0 !important;"><small>Merchant
                      SKU</small></th>
                  <th scope="col" style="padding-top: 0 !important; padding-bottom: 0 !important;"><small>Item
                      SKU</small></th>
                  <th scope="col" style="padding-top: 0 !important; padding-bottom: 0 !important;"><small>Qty</small>
                  </th>
                  <th scope="col" style="padding-top: 0 !important; padding-bottom: 0 !important;"><small>Berat</small>
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order['details'] as $o)
                @php($product = json_decode($o['product_details']))
                <tr>
                  <td style="padding-top: 0 !important; padding-bottom: 0 !important;">{{ $o['order_id'] }}</td>
                  <td style="padding-top: 0 !important; padding-bottom: 0 !important;">{{ $product->name }}</td>
                  <td style="padding-top: 0 !important; padding-bottom: 0 !important;"></td>
                  <td style="padding-top: 0 !important; padding-bottom: 0 !important;"></td>
                  <td style="padding-top: 0 !important; padding-bottom: 0 !important;">{{ $o['qty'] }}</td>
                  <td style="padding-top: 0 !important; padding-bottom: 0 !important;">{{ $o['qty'] * $product->weight }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
</body>

</html>