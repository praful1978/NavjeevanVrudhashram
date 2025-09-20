<?php
// Enable error reporting for debugging (disable on production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$siteTitle = "नवजीवन वृद्धाश्रम – नेर,तालुका नेर, जि. यवतमाळ, महाराष्ट्र";

// Database connection
// $conn = new mysqli("localhost","root","","donations_db");
$conn = new mysqli("localhost","navjeevanvrudhas","~qt}5&g+jfxE","navjeevanvrudhas_donations_db");
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>
<!doctype html>
<html lang="mr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($siteTitle); ?></title>
  <meta name="description" content="ज्येष्ठांचा सन्मान – आपली जबाबदारी. सुरक्षित, स्वच्छ आणि आपुलकीचे वातावरण असलेले वृद्धाश्रम – नेर, यवतमाळ." />
  <link rel="stylesheet" href="/assets/style.css">

</head>
<body>
<?php include "partials/header.php"; ?>

<main class="site-main">
<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    if ($page == "donate") {
        // Show donation form
        ?>
        <h2>Donate</h2>
        <form action="?page=paymentrequest" method="POST">
          <input type="hidden" name="order_id" value="<?php echo "ORD".strtoupper(uniqid()); ?>">
          <label>Amount:</label>
          <input type="number" name="amount" required><br>
          <label>Name:</label>
          <input type="text" name="name" required><br>
          <label>Email:</label>
          <input type="email" name="email" required><br>
          <label>Phone:</label>
          <input type="text" name="phone" required><br>
          <label>Address:</label>
          <input type="text" name="address_line_1" required><br>
          <label>City:</label>
          <input type="text" name="city" required><br>
          <label>State:</label>
          <input type="text" name="state" required><br>
          <label>Zip Code:</label>
          <input type="text" name="zip_code" required><br>
          <label>Country:</label>
          <input type="text" name="country" value="IND" required><br>
          <button type="submit">Proceed to Pay</button>
        </form>
        <?php

    } elseif ($page == "paymentrequest") {
        // Collect POST data and insert into DB
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $order_id = $_POST['order_id'];
            $amount = $_POST['amount'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address_line_1'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip = $_POST['zip_code'];
            $country = $_POST['country'];

            // Save in DB
            $stmt = $conn->prepare("INSERT INTO donations 
                (order_id, amount, name, email, phone, address_line_1, city, state, zip_code, country, currency, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'INR', 'Donation')");
            $stmt->bind_param("sdssssssss", $order_id, $amount, $name, $email, $phone, $address, $city, $state, $zip, $country);
            $stmt->execute();
            $stmt->close();

            // Now include payment request script
            include "paymentrequest.php";
        } else {
            echo "<p>Invalid request.</p>";
        }

    } elseif ($page == "home") {
        include "pages/home.php";
    } elseif ($page == "gallery") {
        include "pages/gallery.php";
    } elseif ($page == "videos") {
        include "pages/videos.php";
    } elseif ($page == "contact") {
        include "pages/contact.php";
    } elseif ($page == "about") {
        include "pages/about.php";
    } elseif ($page == "services") {
        include "pages/services.php";
    } else {
        include "pages/404.php";  // fallback
    }
} else {
    include "pages/home.php"; // default page
}
?>
</main>

