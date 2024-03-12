<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentRequest extends Controller
{
    // Payment Page Example
    public function index()
    {
        return view('payment_page/payment');
    }

    // Payment Request Example
    public function Payment(Request $request)
    {

        $request->validate([
            'amount' => 'required',
            'currency' => 'required',
        ]);

        $account_number = "YOUR_ACCOUNT_NUMBER"; // Your account number
        $api_key = "YOUR_API_KEY"; // Your api key
        $api_secret = "YOUR_SECRET_KEY"; // Your api secret
        $order_id = rand(100000, 999999); // Order ID: Every transaction must be unique! This information will be sent back to your page as a notification.
        $amount = $request->amount * 100; // For 9.99, 9.99 * 100 = 999 should be sent.
        $currency = $request->currency; // TRY, USD, EUR

        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $user_ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $user_ip = $_SERVER["REMOTE_ADDR"];
        }

        $data = array(
            'account_number' => $account_number,
            'order_id' => $order_id,
            'amount' => $amount,
            'currency' => $currency,
            'user_ip' => $user_ip,
        );

        $hash_str = $account_number . $currency . $amount . $user_ip . $order_id;
        $payment_token = base64_encode(hash_hmac('sha256', $hash_str . $api_key, $api_secret, false));

        $headers = array();
        $headers[] = 'api-key: ' . $api_key;
        $headers[] = 'api-secret: ' . $api_secret;
        $headers[] = 'payment-token: ' . $payment_token;

        $ch = curl_init("https://api.paysellcard.com/api/payment/services/create-token");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($result, true);

        if ($result['status'] == 'success') {
            $token = $result['payment_token'];
        }

        return redirect('/')->with('token', $token ?? '');
    }

    // Payment Callback Example
    public function Callback(Request $request)
    {

        $account_number = "YOUR_ACCOUNT_NUMBER"; // Your account number
        $api_key = "YOUR_API_KEY"; // Your api key
        $api_secret = "YOUR_SECRET_KEY"; // Your api secret
        $order_id = $request->order_id; // Order ID: Every transaction must be unique! This information will be sent back to your page as a notification.
        $status = $request->status; // success, fail
        $amount = $request->amount; // For 9.99, 9.99 * 100 = 999 should be sent.

        $hash_str = $order_id . $api_key . $status . $amount . $api_secret;
        $hash = base64_encode(hash_hmac('sha256', $hash_str, $api_secret, false));

        if ($hash != $request->hash) {
            return response()->json(['status' => 'error', 'message' => 'PAYSELLCARD notification failed: bad hash'], 400);
        }

        if ($request->status == 'success') { // Payment is successful
            // Your code here
        } else { // Payment is failed
            // Your code here
        }

        return "OK";
    }
}
