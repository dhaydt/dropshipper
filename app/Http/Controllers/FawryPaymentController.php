<?php

namespace App\Http\Controllers;

use App\User;

class Fawry
{
    public $merchantCode='siYxylRjSPw90cL45E4zDg==';

    public $securityKey='b980b94ca1bc41258316440afe523fea';

    public function __construct()
    {
        $this->merchantCode ='siYxylRjSPw90cL45E4zDg==';

        $this->securityKey = 'b980b94ca1bc41258316440afe523fea';
    }

    public function endpoint($uri)
    {
        return config('fawry.debug') ?
            'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/' . $uri :
            'https://www.atfawry.com/ECommerceWeb/Fawry/' . $uri;
    }

    public function createCardToken($cardNumber, $expiryYear, $expiryMonth, $cvv, $user)
    {
        $result =  $this->post(
            $this->endpoint("cards/cardToken"), [
                "merchantCode" => $this->merchantCode,
                "customerProfileId" => md5(1),
                "customerMobile" => '01759412381',
                "customerEmail" => 'md.alimrun@gmail.com',
                "cardNumber" => $cardNumber,
                "expiryYear" => $expiryYear,
                "expiryMonth" => $expiryMonth,
                "cvv" => $cvv
            ]
        );

        return $result;
    }

    public function listCustomerTokens($user)
    {
        return $this->get(
            $this->endpoint("cards/cardToken"), [
                'merchantCode' => $this->merchantCode,
                'customerProfileId' => md5($user->id),
                'signature' => hash('sha256', $this->merchantCode.md5($user->id).$this->securityKey),
            ]
        );
    }

    public function deleteCardToken($user)
    {
        $result =  $this->delete(
            $this->endpoint("cards/cardToken"), [
                'merchantCode' => $this->merchantCode,
                'customerProfileId' => md5($user->id),
                'signature' => hash(
                    'sha256',
                    $this->merchantCode.
                    md5($user->id).
                    $user->payment_card_fawry_token.
                    $this->securityKey
                )
            ]
        );

        if($result->statusCode == 200) {
            $user->update([
                'payment_card_last_four' => null,
                'payment_card_brand' => null,
                'payment_card_fawry_token' => null,
            ]);
        }

        return $result;
    }

    public function chargeViaCard($merchantRefNum, $user, $amount, $chargeItems = [], $description = null )
    {
        return $this->post(
            $this->endpoint("cards/cardToken"), [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $merchantRefNum,
                'paymentMethod' => 'CARD',
                'cardToken' => $user->payment_card_fawry_token,
                'customerProfileId' => md5($user->id),
                'customerMobile' => $user->mobile,
                'customerEmail' => $user->email,
                'amount' => $amount,
                'currencyCode' => 'EGP',
                'chargeItems' => $chargeItems,
                'description' => $description,
                'signature' => hash(
                    'sha256',
                    $this->merchantCode .
                    $merchantRefNum.
                    md5($user->id) .
                    'CARD' .
                    (float) $amount.
                    $user->payment_card_fawry_token .
                    $this->securityKey
                )
            ]
        );
    }

    public function chargeViaFawry($merchantRefNum, $user, $paymentExpiry, $amount, $chargeItems = [], $description = null )
    {
        return $this->post(
            $this->endpoint("payments/charge"), [
                [
                    'merchantCode' => $this->merchantCode,
                    'merchantRefNum' => $merchantRefNum,
                    'paymentMethod' => 'PAYATFAWRY',
                    'paymentExpiry' => $paymentExpiry,
                    'customerProfileId' => md5($user->id),
                    'customerMobile' => $user->mobile,
                    'customerEmail' => $user->email,
                    'amount' => $amount,
                    'currencyCode' => 'EGP',
                    'chargeItems' => $chargeItems,
                    'description' => $description,
                    'signature' => hash(
                        'sha256',
                        $this->merchantCode .
                        $merchantRefNum.
                        md5($user->id) .
                        'PAYATFAWRY' .
                        (float) $amount .
                        $this->securityKey
                    )
                ]
            ]
        );
    }

    public function refund($fawryRefNumber, $refundAmount, $reason = null)
    {
        return $this->post(
            $this->endpoint("payments/refund"), [
                'merchantCode' => $this->merchantCode,
                'referenceNumber' => $fawryRefNumber,
                'refundAmount' => $refundAmount,
                'reason' => $reason,
                'signature' => hash(
                    'sha256',
                    $this->merchantCode .
                    $fawryRefNumber .
                    number_format((float) $refundAmount, 2) .
                    $this->securityKey
                )
            ]
        );
    }

    public function get($url, $data)
    {
        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';

        $params = trim($params, '&');

        $ch = curl_init($url."?".$params);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($ch));
    }

    public function post($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)))
        );

        return json_decode(curl_exec($ch));
    }

    public function delete($url, $data)
    {
        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';

        $params = trim($params, '&');

        $ch = curl_init($url."?".$params);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($ch));
    }
}

class FawryPaymentController extends Controller
{
    public function payment()
    {
        $fawry = new  Fawry();
        $response= $fawry->createCardToken('5078000000000003','25','02','123',User::where(['id'=>1])->first());

        dd($response);
    }
}
