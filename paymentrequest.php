<?php 
$salt   = "59c0afb5972df27f1a122bb1fdd0b0865b13967c"; // your salt
$apiKey = "7322882a-d56b-41b6-8593-14bc4bb1fb59";    // your api key

// Collect form data
$_POST['order_id']   = "ORD" . time() . rand(1000, 9999);  // unique order id
$_POST['return_url'] = "https://navjeevanvrudhashram.com/paymentResponse.php"; // callback URL
$_POST['mode']       = "LIVE"; // or TEST

// Ensure api_key is set in POST for hash calculation
$_POST['api_key'] = $apiKey;

$hash = hashCalculate($salt, $_POST);

function hashCalculate($salt,$input){
    $hash_columns = [
        'address_line_1', 'address_line_2', 'amount', 'api_key',
        'city', 'country', 'currency', 'description', 'email',
        'mode', 'name', 'order_id', 'phone', 'return_url',
        'state', 'udf1', 'udf2', 'udf3', 'udf4', 'udf5',
        'zip_code'
    ];
    sort($hash_columns);

    $hash_data = $salt;
    foreach ($hash_columns as $column) {
        if (isset($input[$column]) && strlen($input[$column]) > 0) {
            $hash_data .= '|' . trim($input[$column]);
        }
    }
    return strtoupper(hash("sha512", $hash_data));
}
?>
<p>Redirecting...</p>
<form action="https://pgbiz.ablepay.co.in/v2/paymentrequest" id="payment_form" method="POST">
  <input type="hidden" value="<?php echo $hash; ?>" name="hash"/>
<input type="hidden" value="<?php echo $apiKey; ?>" name="api_key"/>
<input type="hidden" name="return_url" value="<?php echo $_POST['return_url'] ?? 'https://navjeevanvrudhashram.com/paymentResponse.php'; ?>" />

<input type="hidden" value="<?php echo $_POST['mode'] ?? ''; ?>" name="mode"/>
<input type="hidden" value="<?php echo $_POST['order_id'] ?? ''; ?>" name="order_id"/>
<input type="hidden" value="<?php echo $_POST['amount'] ?? ''; ?>" name="amount"/>
<input type="hidden" value="<?php echo $_POST['currency'] ?? ''; ?>" name="currency"/>
<input type="hidden" value="<?php echo $_POST['description'] ?? ''; ?>" name="description"/>
<input type="hidden" value="<?php echo $_POST['name'] ?? ''; ?>" name="name"/>
<input type="hidden" value="<?php echo $_POST['email'] ?? ''; ?>" name="email"/>
<input type="hidden" value="<?php echo $_POST['phone'] ?? ''; ?>" name="phone"/>
<input type="hidden" value="<?php echo $_POST['address_line_1'] ?? ''; ?>" name="address_line_1"/>
<input type="hidden" value="<?php echo $_POST['address_line_2'] ?? ''; ?>" name="address_line_2"/>
<input type="hidden" value="<?php echo $_POST['city'] ?? ''; ?>" name="city"/>
<input type="hidden" value="<?php echo $_POST['state'] ?? ''; ?>" name="state"/>
<input type="hidden" value="<?php echo $_POST['zip_code'] ?? ''; ?>" name="zip_code"/>
<input type="hidden" value="<?php echo $_POST['country'] ?? ''; ?>" name="country"/>
<input type="hidden" value="<?php echo $_POST['udf1'] ?? ''; ?>" name="udf1"/>
<input type="hidden" value="<?php echo $_POST['udf2'] ?? ''; ?>" name="udf2"/>
<input type="hidden" value="<?php echo $_POST['udf3'] ?? ''; ?>" name="udf3"/>
<input type="hidden" value="<?php echo $_POST['udf4'] ?? ''; ?>" name="udf4"/>
<input type="hidden" value="<?php echo $_POST['udf5'] ?? ''; ?>" name="udf5"/>

  <noscript><input type="submit" /></noscript>
</form>
<script>
function formAutoSubmit () {
    document.getElementById("payment_form").submit();
}
window.onload = formAutoSubmit;
</script>
