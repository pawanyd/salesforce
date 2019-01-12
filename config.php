<?php
define("CLIENT_ID", "SALESFORCE_CLIENT_ID");
define("CLIENT_SECRET", "SALESFORCE_CLIENT_SECRET");
define("REDIRECT_URI", "https://localhost/example/salesforce/oauth_callback.php");
define("LOGIN_URI", "https://login.salesforce.com");
define("INSTANCE_URL", "https://accessiblemojo.my.salesforce.com");


// we hit a api and get access token from here

define("BASE_URL", "https://accessiblemojo.my.salesforce.com/services/oauth2/token");
define("REFRESH_TOKEN", "SALESFRCE_REFRESH_TOKEN");

$params = "grant_type=refresh_token"."&client_id=" . CLIENT_ID . "&client_secret=" . CLIENT_SECRET. "&refresh_token=" . REFRESH_TOKEN;

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => BASE_URL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $params,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/x-www-form-urlencoded",
  ),
));
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
	$result = json_decode($response);
	$accesss = $result->access_token;
  // print_r($accesss); exit;
	define("ACCESS_TOKEN", $accesss);
}

?>