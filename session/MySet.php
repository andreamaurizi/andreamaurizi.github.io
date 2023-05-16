<?php

$apiKey = "3-P3BN-Lef4-3wFqO";
$setNumber = $_POST['setId'];
echo $setNumber;
// Url per la chiamata all'API


// Url per la chiamata all'API
$url = "https://brickset.com/api/v3.asmx/getSets?apiKey=$apiKey&userHash=&params=%7B%22setNumber%22%3A%22$setNumber%22%7D";

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
    $setID = $set -> setID;
  }

  $url2 = "https://brickset.com/api/v3.asmx/getAdditionalImages?apiKey=$apiKey&setID=$setID";

  // Esegue la chiamata all'API e memorizza la risposta come una stringa
  $response2 = file_get_contents($url);

  // Decodifica la risposta come oggetto JSON
  $data2 = json_decode($response);

  if($data2 -> status == "success"){
    echo $data2;
  }

} else {
  echo "Si è verificato un errore durante la chiamata all'API.";
}


  

?>