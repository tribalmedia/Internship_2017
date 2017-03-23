<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'keys.php';

session_start();
$request_token_secret = $_SESSION["oauth_token_secret"];

$access_token_url = "https://api.twitter.com/oauth/access_token";
$request_method = "POST";

$signature_key = rawurlencode($api_secret) . "&" . rawurlencode($request_token_secret);

$params = array(
    "oauth_consumer_key"     => $api_key,
    "oauth_token"            => $_GET["oauth_token"],
    "oauth_signature_method" => "HMAC-SHA1",
    "oauth_timestamp"        => time(),
    "oauth_verifier"         => $_GET["oauth_verifier"],
    "oauth_nonce"            => microtime(),
    "oauth_version"          => "1.0",
);

foreach ($params as $key => $value) {
    $params[$key] = rawurlencode($value);
}

ksort($params);
$request_params = http_build_query($params, "", "&");
$request_params = rawurlencode($request_params);
$encoded_request_method = rawurlencode($request_method);
$encoded_request_url = rawurlencode($access_token_url);
$signature_data = $encoded_request_method . "&" . $encoded_request_url . "&" . $request_params;
$hash = hash_hmac("sha1", $signature_data, $signature_key, TRUE);
$signature = base64_encode($hash);
$params["oauth_signature"] = $signature;
$header_params = http_build_query($params, "", ",");

$context = array(
    "http" => array(
        "method" => $request_method,
        "header" => array(
            "Authorization: OAuth " . $header_params,
        ),
    ),
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $access_token_url);
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $context["http"]["method"]);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $context["http"]["header"]);
curl_setopt($curl, CURLOPT_TIMEOUT, 5);
$res1 = curl_exec($curl);
$res2 = curl_getinfo($curl);
curl_close($curl);

$response = substr($res1, $res2["header_size"]);
$header = substr($res1, 0, $res2["header_size"]);

$query = [];
parse_str($response, $query);

echo '<p>get below authentication data. (<a href="' . explode("?", $_SERVER["REQUEST_URI"])[0] . '">try again.</a>)</p>';

foreach ($query as $key => $value) {
    echo "<b>" . $key . "</b>: " . $value . "<BR>";
}

require_once __DIR__.DIRECTORY_SEPARATOR.'get_tweet.php';