<?php

$apiKey = "3-1KNg-2P7y-sVK1B";
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
  $imgURL = $sets[0] -> image;
  if(property_exists($imgURL ,'imageURL')){
    $imgURL = $imgURL -> imageURL;
  }else{
    $imgURL = "nessuna immagine";
  }
  print_r($name. " " ."(".$year.")". "|" .$imgURL);

  

} else {
  echo "Si è verificato un errore durante la chiamata all'API.";
}


  

?>