<?php

// API endpoint
$apiUrl = "https://api.brickognize.com/predict/";

// Check if a file was uploaded
if (isset($_FILES['query_image'])) {
    // Get the uploaded file details
    $file = $_FILES['query_image'];

    // Check for any errors during file upload
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Get the temporary file path
        $tempFilePath = $file['tmp_name'];

        // Create a cURL file handle
        $imageFile = curl_file_create($tempFilePath, $file['type'], $file['name']);

        // Create the request body
        $requestData = [
            "query_image" => $imageFile,
        ];

        // Set up the cURL request
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($curl);
        curl_close($curl);

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
        }
    } else {
        die("Error uploading file. Error code: " . $file['error']);
    }
}

?>