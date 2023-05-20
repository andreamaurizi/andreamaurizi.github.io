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
    //echo $response;

    // FINO A QUA FUNZIONA

    // Decode the response JSON
    $responseData = json_decode($response, true);

    // Check if the response is valid JSON
    if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
        die("Error: Invalid JSON response");
    }

    // Check if the response contains predictions
    if (!isset($responseData['items']) || !is_array($responseData['items'])) {
        die("Error: Missing or invalid predictions in the API response");
    }

    // Access the prediction items
    $items = $responseData['items'];

    // Check if predictions exist
    if (empty($items)) {
        die("No predictions found");
    }

    // Create an associative array to store the objects
    $predictions = array();

    // Populate the associative array with prediction objects
    foreach ($items as $item) {
        $prediction = array(
            'id' => isset($item['id']) ? $item['id'] : "Unknown",
            'score' => isset($item['score']) ? $item['score'] : "N/A",
            'name' => isset($item['name']) ? $item['name'] : "Unknown",
            'imageUrl' => isset($item['img_url']) ? $item['img_url'] : ""
        );
        $predictions[$prediction['id']] = $prediction;
    }

    $jsonPredictions = json_encode($predictions);
    print_r($jsonPredictions);

    
} else {
    die("Error uploading file");
}