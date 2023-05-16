<?php

// Takes in the user's image
$base64Image = $_POST["imgBase64"];
$imageData = base64_decode($base64Image);


// BrickOwl API endpoint
$endpoint = 'https://api.brickognize.com/predict/';


// Create the cURL request
$curl = curl_init();

// Set the cURL options
curl_setopt_array($curl, array(
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $imageData,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: image/jpeg',
    ),
));

// Send the cURL request and get the response
$response = curl_exec($curl);

// Check for errors
if (curl_errno($curl)) {
    $error = curl_error($curl);
    // Handle the error
} else {
    // Process the response
    $data = json_decode($response);
    print_r($data);
    // ...
}

// Close the cURL session
curl_close($curl);

?>