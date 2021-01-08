<?php
include_once('../database.php');
require_once('../phpassets/vendor/autoload.php');
//echo $secretKey;die;
//print_r($Api);die;

$getUserData = array();
if (isset($_REQUEST['uid']) && isset($_REQUEST['parent_category']) && isset($_REQUEST['amount'])) {

    $getUserData = $db->selectQuery("select * from w_users where ID = {$_REQUEST['uid']}");
    $getUserData = $getUserData[0];
}

// Create the Razorpay Order
use Razorpay\Api\Api;

$keyId = 'rzp_test_86hEZB9S7Lr2z7';
$secretKey = 'LwE9StdFfsjHPab1DCxZNIHi';

// $keyId = 'rzp_live_m1vqlISlxNfHMt';
// $secretKey = 'BZurHc8e5e1iwC0lybrh56O1';
$displayCurrency = 'INR';
$Api = new Api($keyId, $secretKey);

// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//
$orderData = [
    'receipt' => rand(10, 10000),
    'amount' => $_REQUEST['amount'] * 100, // 2000 rupees in paise
    'currency' => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $Api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR') {
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$checkout = 'automatic';

if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
    $checkout = $_GET['checkout'];
}

$orderID = rand(10, 10000);

$data = [
    "key" => $keyId,
    "amount" => $amount,
    "name" => "D-Life",
    "description" => "Tron Legacy",
    "image" => "https://education.dlife.fun/img/logo.png",
    "prefill" => [
        "name" => $getUserData['uName'],
        "email" => $getUserData['uEmail'],
        "contact" => $getUserData['uMobile'],
    ],
    "notes" => [
        "address" => "Hello World",
        "merchant_order_id" => "12312321",
    ],
    "theme" => [
        "color" => "#F37254"
    ],
    "order_id" => $razorpayOrderId,
];

if ($displayCurrency !== 'INR') {
    $data['display_currency'] = $displayCurrency;
    $data['display_amount'] = $displayAmount;
}

$json = json_encode($data);

//insert payment record into database
$paymentData = array("uID" => $_REQUEST['uid'], "courseID" => $_REQUEST['parent_category'], "orderID" => $razorpayOrderId, "amount" => $amount, "request" => $json);
$db->insertData("w_orders", $paymentData);
?>
<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->


<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1  maximum-scale=1 user-scalable=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<style>
    .razorpay-payment-button {
        color: #fff;
        background-color: #0dc9e0;
        border-color: #0dc9e0;
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
    }

    .rounded {
        border-radius: 1.25rem !important;
    }
</style>

<body>
    <div class="container h-100">
        <div class="row align-items-center h-100">
            <div class="col-12">
                <div class="p-5 bg-primary text-white text-center rounded">
                    <h4>Rs. <?php echo $orderData['amount'] / 100; ?>/-</h4>

                    <form action="success.php" method="POST" name="razorpayform">
                        <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="<?php echo $data['key'] ?>" data-amount="<?php echo $data['amount'] ?>" data-buttontext="Pay With Razorpay" data-currency="INR" data-name="<?php echo $data['name'] ?>" data-image="<?php echo $data['image'] ?>" data-description="<?php echo $data['description'] ?>" data-prefill.name="<?php echo $data['prefill']['name'] ?>" data-prefill.email="<?php echo $data['prefill']['email'] ?>" data-prefill.contact="<?php echo $data['prefill']['contact'] ?>" data-notes.shopping_order_id="<?php echo $orderID; ?>" data-order_id="<?php echo $data['order_id'] ?>" <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount'] ?>" <?php } ?> <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency'] ?>" <?php } ?>>
                        </script>
                        <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
                        <input type="hidden" name="shopping_order_id" value="<?php echo $orderID; ?>">

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>