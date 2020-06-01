<?php

require_once '/var/www/html/sheets/vendor/autoload.php';

$apiKey = "ab0fbab6d7dd33929210fda4b5b83762";
$city = "Novosibirsk";
$url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&lang=ru&units=metric&appid=" . $apiKey;

$ch = curl_init();

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);

$data = json_decode(curl_exec($ch));
curl_close($ch);

$googleAccountKeyFilePath = '/var/www/html/sheets/assets/test-project-279006-a56f0576c1ba.json';
putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath );

$client = new Google_Client();
$client->useApplicationDefaultCredentials();

$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );

$service = new Google_Service_Sheets( $client );

$spreadsheetId = '1Qil_LjMdMTUBcCUoGI9zfLW6NXLFZny57G5FcgDyr40';

$values = [
    ["City:", $data->name],
    ["Temperature:", $data->main->temp_min."°C"],
    ["Humidity:", $data->main->humidity."%"],
    ["Wind:", $data->wind->speed."km/h."],
];
$body = new Google_Service_Sheets_ValueRange( [ 'values' => $values ] );

$options = array( 'valueInputOption' => 'RAW' );

$service->spreadsheets_values->update( $spreadsheetId, 'Danil!A5', $body, $options );

