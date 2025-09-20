<?php
// === Replace with your LIVE Salt ===
$salt = "59c0afb5972df27f1a122bb1fdd0b0865b13967c";
$apiKey = "7322882a-d56b-41b6-8593-14bc4bb1fb59";    // your api key

// Ensure api_key is set in POST for hash calculation
$_POST['api_key'] = $apiKey;

// Include database logging script
include 'paymentlog.php';
// AblePay will send POST data here
$response = $_POST;

// === Recalculate hash for verification ===
$hash_data = $salt
    . "|" . $response['address_line_1']
    . "|" . $response['amount']
    . "|" . $response['api_key']
    . "|" . $response['city']
    . "|" . $response['country']
    . "|" . $response['currency']
    . "|" . $response['description']
    . "|" . $response['email']
    . "|" . $response['mode']
    . "|" . $response['name']
    . "|" . $response['order_id']
    . "|" . $response['phone']
    . "|" . $response['return_url']
    . "|" . $response['state']
    . "|" . $response['zip_code'];

$calculated_hash = strtoupper(hash('sha512', $hash_data));

// Verify hash
if ($calculated_hash === $response['hash']) {
    // ✅ Valid response
    if ($response['status'] === "SUCCESS") {
        echo "<h2>Payment Successful</h2>";
        echo "<p>Order ID: ".$response['order_id']."</p>";
        echo "<p>Transaction ID: ".$response['transaction_id']."</p>";

        // TODO: Update donations table with status SUCCESS
        // $conn->query("UPDATE donations SET status='SUCCESS' WHERE order_id='".$response['order_id']."'");
    } else {
        echo "<h2>Payment Failed</h2>";
        echo "<p>Status: ".$response['status']."</p>";
    }
} else {
    // ❌ Invalid hash → possible tampering
    echo "<h2>Invalid response received</h2>";
}
?>
