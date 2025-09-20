<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// DB connection
$conn = new mysqli("localhost","root","","donations_db");
// $conn = new mysqli("localhost","navjeevanvrudhas","~qt}5&g+jfxE","navjeevanvrudhas_donations_db");
if($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $amount = number_format($_POST['amount'], 2, '.', '');
    $currency = $conn->real_escape_string($_POST['currency']);
    $description = $conn->real_escape_string($_POST['description']);
    $address_line_1 = $conn->real_escape_string($_POST['address_line_1']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $country = $conn->real_escape_string($_POST['country']);
    $zip_code = $conn->real_escape_string($_POST['zip_code']);

    // Generate unique order ID
    $order_id = "ORD" . strtoupper(uniqid());

    // Save donation in DB
    $sql = "INSERT INTO donations
        (name,email,phone,amount,order_id,currency,description,address_line_1,city,state,country,zip_code,mode)
        VALUES ('$name','$email','$phone','$amount','$order_id','$currency','$description','$address_line_1','$city','$state','$country','$zip_code','LIVE')";

    if($conn->query($sql) === TRUE){
        // Redirect to payment_redirect.php which handles the hash and submits to AblePay
        header("Location: return_page.php?order_id=$order_id");
        exit;
    } else {
        echo "Error saving donation: ".$conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Form</title>

  <!-- intl-tel-input CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.min.css"/>
  
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .donation-form {
      background: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.25);
      width: 100%;
      max-width: 400px;
    }

    .donation-form h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 6px;
      color: #333;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      box-sizing: border-box;
    }

    /* Fix intl-tel-input alignment */
    .iti {
      width: 100%;
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: #27ae60;
      color: white;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #219150;
    }
  </style>
</head>
<body>
<form class="donation-form" method="POST" action="/paymentrequest.php">

  <h1>Donate Now</h1>

  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
  </div>
  
  <div class="form-group">
    <label>Address:</label>
    <input type="text" name="address_line_1" placeholder="Enter your address" required>
  </div>  

  <div class="form-group">
    <label for="city">City</label>
    <input type="text" id="city" name="city" placeholder="Enter city" required>
  </div>

  <div class="form-group">
    <label for="state">State</label>
    <input type="text" id="state" name="state" placeholder="Enter state" required>
  </div>

  <div class="form-group">
    <label for="zip_code">Zip Code</label>
    <input type="text" id="zip_code" name="zip_code" placeholder="Enter zip code" required>
  </div>

  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required>
  </div>

  <div class="form-group">
    <label for="phone">Phone</label>
    <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>
  </div>

  <div class="form-group">
    <label for="amount">Donation Amount (₹)</label>
    <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
  </div>

  <div class="form-group">
    <label for="purpose">Purpose</label>
    <select id="purpose" name="description" required>
      <option value="">-- Select Purpose --</option>
      <option value="Food & Shelter">Food & Shelter</option>
      <option value="Medical Help">Medical Help</option>
      <option value="General Donation">General Donation</option>
    </select>
  </div>

  <!-- Hidden fields required by AblePay -->
  <input type="hidden" name="country" value="IND">
  <input type="hidden" name="currency" value="INR">
  <input type="hidden" name="mode" value="LIVE">
  <input type="hidden" name="order_id" value="<?php echo 'ORD'.time(); ?>">
  <input type="hidden" name="return_url" value="https://navjeevanvrudhashram.com/paymentResponse.php">

  <button type="submit">Proceed to Pay</button>
</form>

 <!-- intl-tel-input JS -->
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"></script>

  <script>
    const input = document.querySelector("#phone");
    window.intlTelInput(input, {
      initialCountry: "auto",
      geoIpLookup: function(callback) {
        fetch("https://ipapi.co/json")
          .then(res => res.json())
          .then(data => callback(data.country_code))
          .catch(() => callback("in")); // fallback: India
      },
      preferredCountries: ["in", "us", "gb"],
      separateDialCode: true,
    });
  </script>
  </body>
</html>