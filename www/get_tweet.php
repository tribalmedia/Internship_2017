<?php

$request_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$request_method = 'GET';

$params_a = array(
    'screen_name' => '@stakada7',
    'count'       => 10,
);

$signature_key = rawurlencode($api_secret) . '&' . rawurlencode($access_token_secret);

$params_b = array(
    'oauth_token'            => $access_token,
    'oauth_consumer_key'     => $api_key,
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp'        => time(),
    'oauth_nonce'            => microtime(),
    'oauth_version'          => '1.0',
);

$params_c = array_merge($params_a, $params_b);

ksort($params_c);
$request_params = http_build_query($params_c, '', '&');
$request_params = str_replace(array('+', '%7E'), array('%20', '~'), $request_params);
$request_params = rawurlencode($request_params);
$encoded_request_method = rawurlencode($request_method);
$encoded_request_url = rawurlencode($request_url);
$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params;
$hash = hash_hmac('sha1', $signature_data, $signature_key, TRUE);
$signature = base64_encode($hash);
$params_c['oauth_signature'] = $signature;
$header_params = http_build_query($params_c, '', ',');

$context = array(
    'http' => array(
        'method' => $request_method,
        'header' => array(
            'Authorization: OAuth ' . $header_params,
        ),
    ),
);

if ($params_a) {
    $request_url .= '?' . http_build_query($params_a);
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $request_url);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $context['http']['method']);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $context['http']['header']);
curl_setopt($curl, CURLOPT_TIMEOUT, 5);
$res1 = curl_exec($curl);
$res2 = curl_getinfo($curl);
curl_close($curl);

$json = substr($res1, $res2['header_size']);
$header = substr($res1, 0, $res2['header_size']);

$html = '';

$html .= '<h2>get data.</h2>';
$html .= '<p>get below data.</p>';
$html .= '<h3>BODY(JSON)</h3>';

$tweets = json_decode($json, true);

foreach ($tweets as $tweet) {
    $html .= '<h4>created_at</h4>';
    $html .= '<p>' . $tweet['created_at'] . '</p>';
    $html .= '<h4>id_str</h4>';
    $html .= '<p>' . $tweet['id_str'] . '</p>';
    $html .= '<h4>text</h4>';
    $html .= '<p>' . $tweet['text'] . '</p>';
    echo "\n";
}

$html .= '<p><textarea rows="8">' . $json . '</textarea></p>';
$html .= '<h3>RESPONSE HEADER</h3>';
$html .= '<p><textarea rows="8">' . $header . '</textarea></p>';

$html .= '<h2 style="color:red">Revoke application authentication</h2>';
$html .= '<p>To revoke the authentication with this application, please do from the following page.</p>';
$html .= '<p><a href="https://twitter.com/settings/applications" target="_blank">https://twitter.com/settings/applications</a></p>';

echo $html;