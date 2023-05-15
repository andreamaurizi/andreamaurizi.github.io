<?php

// BrickOwl API endpoint
$endpoint = 'https://api.brickognize.com/predict/';

// API key

// Set the image file path
$imageFilePath = '/path/to/image.jpg';

// Open the file
$fileHandle = fopen($imageFilePath, 'r');

// Read the file contents
$fileContents = fread($fileHandle, filesize($imageFilePath));

// Close the file handle
fclose($fileHandle);

// Create the cURL request
$curl = curl_init();

// Set the cURL options
curl_setopt_array($curl, array(
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $fileContents,
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
    // ...
}

// Close the cURL session
curl_close($curl);

?>