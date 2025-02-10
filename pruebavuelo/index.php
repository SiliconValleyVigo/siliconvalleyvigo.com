<?php
$queryString = http_build_query([
//  'access_key' => getenv('AVIATIONSTACK_API_KEY_ALT')
  'access_key' => getenv('AVIATIONSTACK_API_KEY')
]);

//$ch = curl_init(sprintf('%s?%s', 'https://api.aviationstack.com/v1/flights', $queryString));
$ch = curl_init(sprintf('%s?%s', 'http://api.aviationstack.com/v1/flights', $queryString));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$json = curl_exec($ch);
curl_close($ch);

$api_result = json_decode($json, true);

//echo $json;

foreach ($api_result['data'] as $flight) {
	//echo json_encode($flight);
    if (!$flight['live']['is_ground']) {
        echo sprintf("%s flight %s from %s (%s) to %s (%s) is in the air.",
            $flight['airline']['name'],
            $flight['flight']['iata'],
            $flight['departure']['airport'],
            $flight['departure']['iata'],
            $flight['arrival']['airport'],
            $flight['arrival']['iata']
            ), PHP_EOL;
    }
	echo '<br />';
}