<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'keys.php';

$access_token_secret = "";

$request_token_url = "https://api.twitter.com/oauth/request_token";
$request_method = "POST";

$signature_key = rawurlencode($api_secret) . "&" . rawurlencode($access_token_secret);

$params = array(
    "oauth_callback"         => $callback_url,
    "oauth_consumer_key"     => $api_key,
    "oauth_signature_method" => "HMAC-SHA1",
    "oauth_timestamp"        => time(),
    "oauth_nonce"            => microtime(),
    "oauth_version"          => "1.0",
);

foreach ($params as $key => $value) {
    if ($key == "oauth_callback") {
        continue;
    }
    $params[$key] = rawurlencode($value);
}

ksort($params);
$request_params = http_build_query($params, "", "&");
$request_params = rawurlencode($request_params);
$encoded_request_method = rawurlencode($request_method);
$encoded_request_url = rawurlencode($request_token_url);
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
curl_setopt($curl, CURLOPT_URL, $request_token_url);
curl_setopt($curl, CURLOPT_HEADER, true);
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

if (!$response) {
    echo '<p>[ERROR] Failed to get request token. Please check variables that $api_key, $callback_url in this scripts and the Callback URL set for the application.</p>';
    exit;
}

$query = [];
parse_str($response, $query);

session_start();
session_regenerate_id(true);
$_SESSION["oauth_token_secret"] = $query["oauth_token_secret"];

// authentication per requests
header("Location: https://api.twitter.com/oauth/authorize?oauth_token=" . $query["oauth_token"]);

// authentication at once.
// header( "Location: https://api.twitter.com/oauth/authenticate?oauth_token=" . $query["oauth_token"] ) ;