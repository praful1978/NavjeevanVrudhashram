<?php
// Enable error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// === LIVE CREDENTIALS ===
$salt   = "59c0afb5972df27f1a122bb1fdd0b0865b13967c";
$apiKey = "7322882a-d56b-41b6-8593-14bc4bb1fb59";  // your API key

// Receive POST data from AblePay
$response = $_POST;

// Debug: Save raw response for troubleshooting
file_put_contents("ablepay_debug.log", date("Y-m-d H:i:s") . " => " . print_r($response, true) . "\n", FILE_APPEND);

// === Recalculate hash for verification ===
// ⚠️ IMPORTANT: The field order must match AblePay documentation exactly
$hash_data = $salt
    . "|" . ($response['address_line_1'] ?? '')
    . "|" . ($response['amount'] ?? '')
    . "|" . ($response['api_key'] ?? '')  // Use only what AblePay sends
    . "|" . ($response['city'] ?? '')
    . "|" . ($response['country'] ?? '')
    . "|" . ($response['currency'] ?? '')
    . "|" . ($response['description'] ?? '')
    . "|" . ($response['email'] ?? '')
    . "|" . ($response['mode'] ?? '')
    . "|" . ($response['name'] ?? '')
    . "|" . ($response['order_id'] ?? '')
    . "|" . ($response['phone'] ?? '')
    . "|" . ($response['return_url'] ?? '')
    . "|" . ($response['state'] ?? '')
    . "|" . ($response['zip_code'] ?? '');

$calculated_hash = strtoupper(hash('sha512', $hash_data));

// === Include payment logger ===
$status = $response['status'] ?? 'PENDING';
include 'paymentlog.php';  // this should log with $status

// === Verify hash ===
if (isset($response['hash']) && $calculated_hash === strtoupper($response['hash'])) {
    
    if ($status === "SUCCESS") {
        echo "<h2>✅ Payment Successful</h2>";
        echo "<p>Order ID: " . htmlspecialchars($response['order_id'] ?? '') . "</p>";
        echo "<p>Transaction ID: " . htmlspecialchars($response['transaction_id'] ?? '') . "</p>";

        // Example: update DB donations table (uncomment if needed)
        /*
        $conn->query("UPDATE donations 
                      SET status='SUCCESS' 
                      WHERE order_id='" . $conn->real_escape_string($response['order_id']) . "'");
        */
    } else {
        echo "<h2>❌ Payment Failed</h2>";
        echo "<p>Status: " . htmlspecialchars($status) . "</p>";
    }

} else {
    // ❌ Invalid hash → possible tampering
    echo "<h2>⚠️ Invalid response received</h2>";
}
?>
