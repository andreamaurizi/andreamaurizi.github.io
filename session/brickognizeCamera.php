<?php

// Questo è l'endpoint dell'API di brickognize
$apiUrl = "https://api.brickognize.com/predict/";



// Controlliamo se il file sia stato ricevuto
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $file = $_FILES['image'];

    // Creiamo un fileHandle cURL
    $imageFile = new CURLFile($file['tmp_name'], $file['type'], $file['name']);

    // Inseriamo nel campo query_image il nostro imageFile
    $requestData = [
        'query_image' => $imageFile,
    ];

    //Facciamo il setup della richiesta cURL
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Eseguiamo la richiesta cURL
    $response = curl_exec($curl);

    //  Controllo di successo della richiesta
    if ($response === false) {
        die("Error: " . curl_error($curl));
    }

    // Decodifichiamo la risposta JSON
    $responseData = json_decode($response, true);

    if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
        die("Error: Invalid JSON response");
    }

    // Controlliamo se la risposta contenga predizioni
    if (!isset($responseData['items']) || !is_array($responseData['items'])) {
        die("Error: Missing or invalid predictions in the API response");
    }

    $items = $responseData['items'];

    // Controlliamo se esistano predizioni
    if (empty($items)) {
        die("No predictions found");
    }

    $predictions = array();

    // Riempiamo l'array predidiction con i vari elementi restituiti dall'API
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