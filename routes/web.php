<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $gateway = new Braintree\Gateway([
        'environment' => 'sandbox',
        'merchantId' => '6k6m4rt6hmfwpqcf',
        'publicKey' => '68td22tzfk475g8b',
        'privateKey' => '3ebce2639ade8dd638023434949ad1c1'
    ]);
    // genero token         !!!
    $token = $gateway->ClientToken()->generate();

    return view('welcome', [
        'token' => $token
    ]);
});

Route::post('/checkout', function (Request $request) {
    $gateway = new Braintree\Gateway([
        'environment' => 'sandbox',
        'merchantId' => '6k6m4rt6hmfwpqcf',
        'publicKey' => '68td22tzfk475g8b',
        'privateKey' => '3ebce2639ade8dd638023434949ad1c1'
    ]);

    $amount = $request->amount;
    $nonce = $request->payment_method_nonce;

    $result = $gateway->transaction()->sale([
        'amount' => $amount,
        'paymentMethodNonce' => $nonce,
        'customer' => [
            'firstName' => 'Lucio',
            'lastName' => 'Ghedina',
            'email' => 'lucio@ghedina.com',
        ],
        'options' => [
            'submitForSettlement' => true
        ]
    ]);

    if ($result->success) {
        $transaction = $result->transaction;
        // header("Location: transaction.php?id=" . $transaction->id);

        return back()->with('success_message', 'Transaction successful. The ID is:'. $transaction->id);
    } else {
        $errorString = "";

        foreach ($result->errors->deepAll() as $error) {
            $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
        }

        // $_SESSION["errors"] = $errorString;
        // header("Location: index.php");
        return back()->withErrors('An error occurred with the message: '.$result->message);
    }
});

Route::get('/hosted', function () {
    $gateway = new Braintree\Gateway([
        'environment' => 'sandbox',
        'merchantId' => '6k6m4rt6hmfwpqcf',
        'publicKey' => '68td22tzfk475g8b',
        'privateKey' => '3ebce2639ade8dd638023434949ad1c1'
    ]);

    $token = $gateway->ClientToken()->generate();

    return view('hosted', [
        'token' => $token
    ]);
});

