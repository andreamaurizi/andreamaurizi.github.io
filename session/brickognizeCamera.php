<?php

// API endpoint
$apiUrl = "https://api.brickognize.com/predict/";

//var_dump($_FILES);



// Check if the image file is received
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    // Get the uploaded file details
    $file = $_FILES['image'];

    // Create a cURL file handle
    $imageFile = new CURLFile($file['tmp_name'], $file['type'], $file['name']);

    // Create the request body
    $requestData = [
        'query_image' => $imageFile,
    ];

    // Set up the cURL request
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check if the request was successful
    if ($response === false) {
        die("Error: " . curl_error($curl));
    }

    // Display the response content for debugging
    echo "Response: " . $response;

    // Decode the response JSON
    $responseData = json_decode($response, true);

    // Check if the response is valid JSON
    if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
        die("Error: Invalid JSON response");
    }

    // Check if the response contains an error message
    if (isset($responseData['detail'])) {
        $errors = $responseData['detail'];
        die("Error: " . $errors[0]['msg']);
    }

    // Check if the response contains predictions
    if (!isset($responseData['predictions']) || !is_array($responseData['predictions'])) {
        die("Error: Missing or invalid predictions in the API response");
    }

    // Access the prediction results
    $predictions = $responseData['predictions'];

    // Check if predictions exist
    if (empty($predictions)) {
        die("No predictions found");
    }

    // Display the predictions
    foreach ($predictions as $prediction) {
        $label = isset($prediction['label']) ? $prediction['label'] : "Unknown";
        $confidence = isset($prediction['confidence']) ? $prediction['confidence'] : "N/A";

        echo "Label: $label<br>";
        echo "Confidence: $confidence<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
    }
} else {
    die("Error uploading file");
}