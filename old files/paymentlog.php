<?php
// Database connection

$conn = new mysqli("localhost","root","","donations_db");
// $conn = new mysqli("localhost","navjeevanvrudhas","~qt}5&g+jfxE","navjeevanvrudha_donations_db");
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Example payment response (replace with your gateway response)
$response = $_POST; // assuming gateway POSTs data
$order_id = $response['order_id'] ?? 'UNKNOWN';

// Determine status based on response
if (isset($response['payment_status'])) {
    $status = strtoupper($response['payment_status']); // e.g., SUCCESS or FAILED
    if (!in_array($status, ['SUCCESS', 'FAILED'])) {
        $status = 'PENDING';
    }
} else {
    $status = 'PENDING';
}

// Convert response to JSON
$response_json = json_encode($response);

// Insert into payment_log
$sql = "INSERT INTO payment_log (order_id, status, response_json) 
        VALUES (:order_id, :status, :response_json)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':order_id' => $order_id,
    ':status' => $status,
    ':response_json' => $response_json
]);

echo "Payment log saved successfully with status: $status";
?>
