<?php

$apiKey = "3-8aLX-B2Qx-jPCCT";
$setNumber = $_POST['setId'];
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
  //echo "Ci sono " . count($sets) . " set corrispondenti alla tua ricerca.\n";
  $setID = $sets[0] -> setID;
  $name = $sets[0] -> name;
  $year = $sets[0] -> year;
  print_r($name. " " ."(".$year.")". ",");

  $url2 = "https://brickset.com/api/v3.asmx/getAdditionalImages?apiKey=$apiKey&setID=$setID";

  // Esegue la chiamata all'API e memorizza la risposta come una stringa
  $response2 = file_get_contents($url2);

  // Decodifica la risposta come oggetto JSON
  $data2 = json_decode($response2);

  if($data2 -> status == "success"){
     $imgs = $data2->additionalImages;
      $imageURL = $imgs[0]-> thumbnailURL;
      print_r($imageURL);
  }

} else {
  echo "Si è verificato un errore durante la chiamata all'API.";
}


  

?>