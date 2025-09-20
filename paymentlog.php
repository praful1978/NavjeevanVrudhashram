<?php
// Database connection
$host = "localhost";
$db = "navjeevanvrudhas_donations_db";
$user = "navjeevanvrudhas";
$pass = "~qt}5&g+jfxE";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Collect POST response from payment gateway
$response = $_POST;
$order_id = $response['order_id'] ?? 'UNKNOWN';

// Determine payment status
$status = 'PENDING';
if (!empty($response['payment_status'])) {
    $status_input = strtoupper($response['payment_status']);
    if (in_array($status_input, ['SUCCESS', 'FAILED'])) {
        $status = $status_input;
    }
}

// Prepare values for database
$response_json = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$received_at = date('Y-m-d H:i:s');

// Insert into payment_log
$sql = "INSERT INTO payment_log (order_id, response_json, received_at, status) 
        VALUES (:order_id, :response_json, :received_at, :status)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':order_id' => $order_id,
    ':response_json' => $response_json,
    ':received_at' => $received_at,
    ':status' => $status
]);

echo "Payment log saved successfully with status: $status";
?>
