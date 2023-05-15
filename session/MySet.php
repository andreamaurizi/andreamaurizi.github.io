<?php

$apiKey = "3-P3BN-Lef4-3wFqO";


// Url per la chiamata all'API


// Url per la chiamata all'API
$url = "https://brickset.com/api/v3.asmx/getSets?apiKey=$apiKey&params={'setNumber':'30654-1'}";

// Esegue la chiamata all'API e memorizza la risposta come una stringa
$response = file_get_contents($url);

// Decodifica la risposta come oggetto JSON
$data = json_decode($response);


// Esegue il controllo della risposta
if ($data->status == "success") {
    // La chiamata all'API è stata eseguita correttamente, puoi accedere ai dati restituiti
    $sets = $data->sets;
    // Esempi di accesso ai dati restituiti
    echo "Ci sono " . count($sets) . " set corrispondenti alla tua ricerca.\n";
    foreach ($sets as $set) {
      echo $set->number . " - " . $set->name . " (" . $set->year . ")\n";
    }
  } else {
    echo "Si è verificato un errore durante la chiamata all'API.";
  }
  

?>