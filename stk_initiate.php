<?php
if (isset($_POST['submit'])) {

  date_default_timezone_set('Africa/Nairobi');

  # access token
  $consumerKey = ''; //Fill with your app Consumer Key
  $consumerSecret = '';  //Fill with your app Consumer Secret
  //ACCESS TOKEN URL

  # define the variables
  $BusinessShortCode = '';
  $Passkey = '';    

  $PartyA = $_POST['phone']; // This is your phone number, 
  $AccountReference = 'AMOH-API TEST';
  $TransactionDesc = 'Account Activation';
  
  # Get the timestamp, format YYYYmmddhms
  $Timestamp = date('YmdHis');    
  
  # Get the base64 encoded string -> $password
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

  # header for access token
  $headers = ['Content-Type:application/json; charset=utf8'];

  # M-PESA endpoint urls
  $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
  $initiate_url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  # callback url
  $CallBackURL = 'https://65d4-2c0f-6300-214-fa00-502b-9c8a-8c4-62f6.ngrok-free.app/mypesa/callback.php';  

  $curl = curl_init($access_token_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
  $result = curl_exec($curl);
  $result = json_decode($result);
  $access_token = $result->access_token;  
  curl_close($curl);

  # header for stk push
  $stkheader = ['Content-Type:application/json', 'Authorization:Bearer '.$access_token];

  # initiating the transaction
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

  $curl_post_data = array(
      'BusinessShortCode' => $BusinessShortCode,
      'Password' => $Password,
      'Timestamp' => $Timestamp,
      'TransactionType' => 'CustomerPayBillOnline',
      'Amount' => 1,
      'PartyA' => $PartyA,
      'PartyB' => $BusinessShortCode,
      'PhoneNumber' => $PartyA,
      'CallBackURL' => $CallBackURL,
      'AccountReference' => $AccountReference,
      'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  $curl_response = curl_exec($curl);
  curl_close($curl);

  // Decode the response
  $response = json_decode($curl_response, true);

  // Check if ResponseCode is "0"
  if ($response['ResponseCode'] === "0") {
      $merchantRequestID = $response['MerchantRequestID'];
      // Redirect to waiting.php with MerchantRequestID in the URL
      header("Location: waiting.php?merchantRequestID=$merchantRequestID");
  } else {
      // Output the ResponseDescription
      echo $response['ResponseDescription'];
  }
  
  exit();
}
?>
