@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Choose Payment Method'))

@push('css_or_js')
    <style>
        .stripe-button-el {
            display: none !important;
        }

        .razorpay-payment-button {
            display: none !important;
        }
    </style>

    {{--stripe--}}
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
    {{--stripe--}}
@endpush

@section('content')
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <div class="col-md-12 mb-5 pt-5">
                <div class="feature_header" style="background: #dcdcdc;line-height: 1px">
                    <span>{{ \App\CPU\translate('Metode _pembayaran')}}</span>
                </div>
            </div>

            <section class="col-lg-8">
                <hr>
                <div class="checkout_details mt-3">
                @include('web-views.partials._checkout-steps',['step'=>3])

                <!-- Payment methods accordion-->
                @php($ship = App\Model\CartShipping::where('cart_group_id', session()->get('cart_group_id'))->first())
                @if ($ship->shipping_cost !== "0.00")
                <h2 class="h6 pb-3 mb-2 mt-5">{{\App\CPU\translate('Pilih_pembayaran')}}</h2>

                <div class="row">
                    @php($config=\App\CPU\Helpers::get_business_settings('cash_on_delivery'))
                    @if($config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <a class="btn btn-block"
                                       href="{{route('checkout-complete',['payment_method'=>'cash_on_delivery'])}}">
                                        <img width="150" style="margin-top: -10px"
                                             src="{{asset('public/assets/front-end/img/cod.png')}}"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php($config=\App\CPU\Helpers::get_business_settings('ssl_commerz_payment'))
                    @if($config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <form action="{{ url('/pay-ssl') }}" method="POST" class="needs-validation">
                                        <input type="hidden" value="{{ csrf_token() }}" name="_token"/>
                                        <button class="btn btn-block" type="submit">
                                            <img width="150"
                                                 src="{{asset('public/assets/front-end/img/sslcomz.png')}}"/>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php($config=\App\CPU\Helpers::get_business_settings('paypal'))
                    @if($config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <form class="needs-validation" method="POST" id="payment-form"
                                          action="{{route('pay-paypal')}}">
                                        {{ csrf_field() }}
                                        <button class="btn btn-block" type="submit">
                                            <img width="150"
                                                 src="{{asset('public/assets/front-end/img/paypal.png')}}"/>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php($user = auth('customer')->user())
                <div class="col-md-6 mb-4" style="cursor: pointer">
                    <div class="card">
                        <div class="card-body" style="height: 100px">
                            <form class="needs-validation" method="POST" id="payment-form"
                                action="{{route('xendit-payment.vaInvoice')}}">

                                <input type="hidden" name="type" value="OVO">
                                {{-- <input class="price" type="hidden" name="price" value="price"> --}}
                                {{ csrf_field() }}
                                <button class="btn btn-block" type="submit">
                                    <img width="150" style="margin-top: -10px"
                                    src="{{asset('public/assets/front-end/img/ovo.png')}}" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" style="cursor: pointer">
                    <div class="card">
                        <div class="card-body" style="height: 100px">
                            <form class="needs-validation" method="POST" id="payment-form"
                                action="{{route('xendit-payment.vaInvoice')}}">

                                <input type="hidden" name="type" value="DANA">
                                {{-- <input class="price" type="hidden" name="price" value="price"> --}}
                                {{ csrf_field() }}
                                <button class="btn btn-block" type="submit">
                                    <img width="150" style="margin-top: -10px"
                                    src="{{asset('public/assets/front-end/img/dana.png')}}" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" style="cursor: pointer">
                    <div class="card">
                        <div class="card-body" style="height: 100px">
                            <form class="needs-validation" method="POST" id="payment-form"
                                action="{{route('xendit-payment.vaInvoice')}}">

                                <input type="hidden" name="type" value="BNI">
                                {{-- <input class="price" type="hidden" name="price" value="price"> --}}
                                {{ csrf_field() }}
                                <button class="btn btn-block" type="submit">
                                    <img width="150" style="margin-top: -10px"
                                    src="{{asset('public/assets/front-end/img/bni.png')}}" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                    @php($coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
                    @php($amount = \App\CPU\CartManager::cart_grand_total() - $coupon_discount)

                    @php($config=\App\CPU\Helpers::get_business_settings('stripe'))
                    @if($config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <button class="btn btn-block" type="button" id="checkout-button">
                                        <i class="czi-card"></i> {{\App\CPU\translate('Credit / Debit card ( Stripe )')}}
                                    </button>
                                    <script type="text/javascript">
                                        // Create an instance of the Stripe object with your publishable API key
                                        var stripe = Stripe('{{$config['published_key']}}');
                                        var checkoutButton = document.getElementById("checkout-button");
                                        checkoutButton.addEventListener("click", function () {
                                            fetch("{{route('pay-stripe')}}", {
                                                method: "GET",
                                            }).then(function (response) {
                                                console.log(response)
                                                return response.text();
                                            }).then(function (session) {
                                                /*console.log(JSON.parse(session).id)*/
                                                return stripe.redirectToCheckout({sessionId: JSON.parse(session).id});
                                            }).then(function (result) {
                                                if (result.error) {
                                                    alert(result.error.message);
                                                }
                                            }).catch(function (error) {
                                                console.error("{{\App\CPU\translate('Error')}}:", error);
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php($config=\App\CPU\Helpers::get_business_settings('razor_pay'))
                    @php($inr=\App\Model\Currency::where(['symbol'=>'₹'])->first())
                    @php($usd=\App\Model\Currency::where(['code'=>'USD'])->first())
                    @if(isset($inr) && isset($usd) && $config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <form action="{!!route('payment-razor')!!}" method="POST">
                                    @csrf
                                    <!-- Note that the amount is in paise = 50 INR -->
                                        <!--amount need to be in paisa-->
                                        <script src="https://checkout.razorpay.com/v1/checkout.js"
                                                data-key="{{ \Illuminate\Support\Facades\Config::get('razor.razor_key') }}"
                                                data-amount="{{(round(\App\CPU\Convert::usdToinr($amount)))*100}}"
                                                data-buttontext="Pay {{(\App\CPU\Convert::usdToinr($amount))*100}} INR"
                                                data-name="{{\App\Model\BusinessSetting::where(['type'=>'company_name'])->first()->value}}"
                                                data-description=""
                                                data-image="{{asset('storage/company/'.\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)}}"
                                                data-prefill.name="{{auth('customer')->user()->f_name}}"
                                                data-prefill.email="{{auth('customer')->user()->email}}"
                                                data-theme.color="#ff7529">
                                        </script>
                                    </form>
                                    <button class="btn btn-block" type="button"
                                            onclick="$('.razorpay-payment-button').click()">
                                        <img width="150"
                                             src="{{asset('public/assets/front-end/img/razor.png')}}"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif


                    @php($config=\App\CPU\Helpers::get_business_settings('paystack'))
                    @if($config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    @php($config=\App\CPU\Helpers::get_business_settings('paystack'))
                                    @php($order=\App\Model\Order::find(session('order_id')))
                                    <form method="POST" action="{{ route('paystack-pay') }}" accept-charset="UTF-8"
                                          class="form-horizontal"
                                          role="form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-8 col-md-offset-2">
                                                <input type="hidden" name="email"
                                                       value="{{auth('customer')->user()->email}}"> {{-- required --}}
                                                <input type="hidden" name="orderID"
                                                       value="{{session('cart_group_id')}}">
                                                <input type="hidden" name="amount"
                                                       value="{{\App\CPU\Convert::usdTozar($amount*100)}}"> {{-- required in kobo --}}
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="currency"
                                                       value="ZAR">
                                                <input type="hidden" name="metadata"
                                                       value="{{ json_encode($array = ['key_name' => 'value',]) }}"> {{-- For other necessary things you want to add to your payload. it is optional though --}}
                                                <input type="hidden" name="reference"
                                                       value="{{ Paystack::genTranxRef() }}"> {{-- required --}}
                                                <p>
                                                    <button class="paystack-payment-button" style="display: none"
                                                            type="submit"
                                                            value="Pay Now!"></button>
                                                </p>
                                            </div>
                                        </div>
                                    </form>
                                    <button class="btn btn-block" type="button"
                                            onclick="$('.paystack-payment-button').click()">
                                        <img width="100"
                                             src="{{asset('public/assets/front-end/img/paystack.png')}}"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif


                    @php($myr=\App\Model\Currency::where(['code'=>'MYR'])->first())
                    @php($usd=\App\Model\Currency::where(['code'=>'usd'])->first())
                    @php($config=\App\CPU\Helpers::get_business_settings('senang_pay'))
                    @if(isset($myr) && isset($usd) && $config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    @php($config=\App\CPU\Helpers::get_business_settings('senang_pay'))
                                    @php($user=auth('customer')->user())
                                    @php($secretkey = $config['secret_key'])
                                    @php($data = new \stdClass())
                                    @php($data->merchantId = $config['merchant_id'])
                                    @php($data->detail = 'payment')
                                    @php($data->order_id = session('cart_group_id'))
                                    @php($data->amount = \App\CPU\Convert::usdTomyr($amount))
                                    @php($data->name = $user->f_name.' '.$user->l_name)
                                    @php($data->email = $user->email)
                                    @php($data->phone = $user->phone)
                                    @php($data->hashed_string = md5($secretkey . urldecode($data->detail) . urldecode($data->amount) . urldecode($data->order_id)))

                                    <form name="order" method="post"
                                          action="https://{{env('APP_MODE')=='live'?'app.senangpay.my':'sandbox.senangpay.my'}}/payment/{{$config['merchant_id']}}">
                                        <input type="hidden" name="detail" value="{{$data->detail}}">
                                        <input type="hidden" name="amount" value="{{$data->amount}}">
                                        <input type="hidden" name="order_id" value="{{$data->order_id}}">
                                        <input type="hidden" name="name" value="{{$data->name}}">
                                        <input type="hidden" name="email" value="{{$data->email}}">
                                        <input type="hidden" name="phone" value="{{$data->phone}}">
                                        <input type="hidden" name="hash" value="{{$data->hashed_string}}">
                                    </form>

                                    <button class="btn btn-block" type="button"
                                            onclick="document.order.submit()">
                                        <img width="100"
                                             src="{{asset('public/assets/front-end/img/senangpay.png')}}"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif


                    @php($config=\App\CPU\Helpers::get_business_settings('paymob_accept'))
                    @if($config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <form class="needs-validation" method="POST" id="payment-form-paymob"
                                          action="{{route('paymob-credit')}}">
                                        {{ csrf_field() }}
                                        <button class="btn btn-block" type="submit">
                                            <img width="150"
                                                 src="{{asset('public/assets/front-end/img/paymob.png')}}"/>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif


                    @php($config=\App\CPU\Helpers::get_business_settings('bkash'))
                    @if(isset($config)  && $config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <button class="btn btn-block" id="bKash_button" onclick="BkashPayment()">
                                        <img width="100" src="{{asset('public/assets/front-end/img/bkash.png')}}"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php($config=\App\CPU\Helpers::get_business_settings('paytabs'))
                    @if(isset($config)  && $config['status'])
                        <div class="col-md-6 mb-4" style="cursor: pointer">
                            <div class="card">
                                <div class="card-body" style="height: 100px">
                                    <button class="btn btn-block"
                                            onclick="location.href='{{route('paytabs-payment')}}'"
                                            style="margin-top: -11px">
                                        <img width="150"
                                             src="{{asset('public/assets/front-end/img/paytabs.png')}}"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @else
                @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
                @php($cart=\App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get()->groupBy('cart_group_id'))
                <div class="row">
                    <!-- List of items-->
                    <div class="cart_information px-4 col-md-12 mb-4">
                            <!-- Payment methods accordion-->
                            <h2 class="h6 w-100 mt-5" style="color: red; font-size: 12px;">{{\App\CPU\translate('Because_you_changed_the_address')}}</h2>
                            <h2 class="h6 pb-3 mb-2">{{\App\CPU\translate('Please_choose_the_shipping_method_again')}}</h2>
                            @foreach($cart as $group_key=>$group)
                                @foreach($group as $cart_key=>$cartItem)

                                    @if($cart_key==$group->count()-1)
                                    <!-- choosen shipping method-->
                            @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())
                            @if(isset($choosen_shipping)==false)
                            @php($choosen_shipping['shipping_method_id']=0)
                            @endif

                            @if($shippingMethod=='sellerwise_shipping')
                            @php($shippings=\App\CPU\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is'],$cartItem['product_id']))
                            <div class="row">
                                {{-- {{ dd($shippings) }} --}}
                                <div class="col-12">
                                    <select class="form-control"
                                        onchange="set_shipping_id(this.value,'{{$cartItem['cart_group_id']}}')">
                                        <option>{{\App\CPU\translate('choose_shipping_method')}}</option>
                                        @if ($shippings[0][0][0]['costs'])
                                        @foreach($shippings[0][0][0]['costs'] as $ship)
                                        {{-- {{ dd($ship) }} --}}
                                        <option value="{{'JNE-'.$ship['service'].','.$ship['cost'][0]['value']}}"
                                            {{$choosen_shipping['shipping_method_id']==$ship['service']?'selected':''}}>
                                            {{"JNE - ".''.$ship['service'].' ( '.$ship['cost'][0]['etd'].' Days)
                                           '.\App\CPU\Helpers::currency_converter(\App\CPU\Convert::idrTousd($ship['cost'][0]['value']))}}
                                        </option>
                                        @endforeach
                                        @endif

                                        @if ($shippings[0][1][0]['costs'])
                                        @foreach($shippings[0][1][0]['costs'] as $ship)
                                        {{-- {{ dd($ship) }} --}}
                                       <option value="{{'TIKI- '.$ship['service'].','.$ship['cost'][0]['value']}}"
                                            {{$choosen_shipping['shipping_method_id']==$ship['service']?'selected':''}}>
                                            {{"TIKI - ".''.$ship['service'].' ( '.$ship['cost'][0]['etd'].' Days)
                                           '.\App\CPU\Helpers::currency_converter(\App\CPU\Convert::idrTousd($ship['cost'][0]['value']))}}
                                        </option>
                                        @endforeach
                                        @endif

                                        @if ($shippings[0][2][0]['costs'])
                                        @foreach($shippings[0][2][0]['costs'] as $ship)
                                        {{-- {{ dd($ship) }} --}}
                                        <option value="{{'SiCepat- '.$ship['service'].','.$ship['cost'][0]['value']}}"
                                            {{$choosen_shipping['shipping_method_id']==$ship['service']?'selected':''}}>
                                            {{"SiCepat - ".''.$ship['service'].' ( '.$ship['cost'][0]['etd'].' Days)
                                           '.\App\CPU\Helpers::currency_converter(\App\CPU\Convert::idrTousd($ship['cost'][0]['value']))}}
                                        </option>
                                        @endforeach
                                        @endif
                                        @foreach($shippings[1] as $shipping)
                                        <option value="{{$shipping['id']}}"
                                            {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                            {{$shipping['title'].' ( '.$shipping['duration'].' )
                                            '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endforeach
                            <div class="mt-3"></div>
                            @endforeach


                @php($coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
                @php($amount = \App\CPU\CartManager::cart_grand_total() - $coupon_discount)
                @endif

                    <!-- Navigation (desktop)-->
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <a class="btn btn-secondary btn-block" href="{{route('checkout-details')}}">
                                <span class="d-none d-sm-inline">{{\App\CPU\translate('Back to Shipping')}}</span>
                                <span class="d-inline d-sm-none">{{\App\CPU\translate('Back')}}</span>
                            </a>
                        </div>
                        <div class="col-4"></div>
                    </div>
                </div>
            </section>
            <!-- Sidebar-->
            @include('web-views.partials._order-summary')
        </div>
    </div>
@endsection

@push('script')

    @if(env('APP_MODE')=='live')
        <script id="myScript"
                src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
    @else
        <script id="myScript"
                src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>
    @endif

    <script>
        setTimeout(function () {
            $('.stripe-button-el').hide();
            $('.razorpay-payment-button').hide();
        }, 10)
    </script>


<script>
    cartQuantityInitialize();

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }
</script>


    <script type="text/javascript">
        function BkashPayment() {
            $('#loading').show();
            // get token
            $.ajax({
                url: "{{ route('bkash-get-token') }}",
                type: 'POST',
                contentType: 'application/json',
                success: function (data) {
                    $('#loading').hide();
                    $('pay-with-bkash-button').trigger('click');
                    if (data.hasOwnProperty('msg')) {
                        showErrorMessage(data) // unknown error
                    }
                },
                error: function (err) {
                    $('#loading').hide();
                    showErrorMessage(err);
                }
            });
        }

        let paymentID = '';
        bKash.init({
            paymentMode: 'checkout',
            paymentRequest: {},
            createRequest: function (request) {
                setTimeout(function () {
                    createPayment(request);
                }, 2000)
            },
            executeRequestOnAuthorization: function (request) {
                $.ajax({
                    url: '{{ route('bkash-execute-payment') }}',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        "paymentID": paymentID
                    }),
                    success: function (data) {
                        if (data) {
                            if (data.paymentID != null) {
                                BkashSuccess(data);
                            } else {
                                showErrorMessage(data);
                                bKash.execute().onError();
                            }
                        } else {
                            $.get('{{ route('bkash-query-payment') }}', {
                                payment_info: {
                                    payment_id: paymentID
                                }
                            }, function (data) {
                                if (data.transactionStatus === 'Completed') {
                                    BkashSuccess(data);
                                } else {
                                    createPayment(request);
                                }
                            });
                        }
                    },
                    error: function (err) {
                        bKash.execute().onError();
                    }
                });
            },
            onClose: function () {
                // for error handle after close bKash Popup
            }
        });

        function createPayment(request) {
            // because of createRequest function finds amount from this request
            request['amount'] = "{{round(\App\CPU\Convert::usdTobdt($amount),2)}}"; // max two decimal points allowed
            $.ajax({
                url: '{{ route('bkash-create-payment') }}',
                data: JSON.stringify(request),
                type: 'POST',
                contentType: 'application/json',
                success: function (data) {
                    $('#loading').hide();
                    if (data && data.paymentID != null) {
                        paymentID = data.paymentID;
                        bKash.create().onSuccess(data);
                    } else {
                        bKash.create().onError();
                    }
                },
                error: function (err) {
                    $('#loading').hide();
                    showErrorMessage(err.responseJSON);
                    bKash.create().onError();
                }
            });
        }

        function BkashSuccess(data) {
            $.post('{{ route('bkash-success') }}', {
                payment_info: data
            }, function (res) {
                @if(session()->has('payment_mode') && session('payment_mode') == 'app')
                    location.href = '{{ route('payment-success')}}';
                @else
                    location.href = '{{route('order-placed')}}';
                @endif
            });
        }

        function showErrorMessage(response) {
            let message = 'Unknown Error';
            if (response.hasOwnProperty('errorMessage')) {
                let errorCode = parseInt(response.errorCode);
                let bkashErrorCode = [2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014,
                    2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030,
                    2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 2045, 2046,
                    2047, 2048, 2049, 2050, 2051, 2052, 2053, 2054, 2055, 2056, 2057, 2058, 2059, 2060, 2061, 2062,
                    2063, 2064, 2065, 2066, 2067, 2068, 2069, 503,
                ];
                if (bkashErrorCode.includes(errorCode)) {
                    message = response.errorMessage
                }
            }
            Swal.fire("Payment Failed!", message, "error");
        }
    </script>
@endpush
