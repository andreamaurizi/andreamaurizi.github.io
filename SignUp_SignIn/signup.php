<!DOCTYPE html>
<html lang="en">
<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
  header("Location: /");
} else {
  $password = "postgres";
  $host = "localhost";
  $dbname = "ReBuild";
  $username = "postgres";
  $port = "5432";
  $conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";



  $pg_connect = pg_connect($conn_string)
    or die('Could not connect: ' . pg_last_error());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>


  <?php

  $die = 0;

  if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    echo "<span>Inserire una mail valida</span>";
    $die = 1;
  }

  if (strlen($_POST["password"]) < 8 && $die == 0) {
    echo "<span style='font-size: 12px'>La password deve contenere almeno 8 caratteri</span>";
    $die = 1;
  }

  if (!preg_match("/[a-z]/i", $_POST["password"]) && $die == 0) {
    echo "<span style='font-size: 12px'>La password deve contenere almeno una lettera</span>";
    $die = 1;
  }

  if (!preg_match("/[0-9]/", $_POST["password"]) && $die == 0) {
    echo "<span style='font-size: 12px'>La password deve contenere almeno un numero</span>";
    $die = 1;
  }

  if ($_POST["password"] !== $_POST["conferma_password"] && $die == 0) {
    echo "<span style='font-size: 12px'>La password deve coincidere<span>";
    $die = 1;
  }
  if ($pg_connect && $die == 0) {
    $email = $_POST["email"];
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);


    $email_escaped = pg_escape_string($email);
    $q1 = "select * from utente where email= $1";
    $result = pg_query_params($pg_connect, $q1, array($email));
    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      echo "<span> Indirizzo email non e' disponibile</h1>";
                
    } else {
      $q2 = "insert into utente(email, password_hash) values ($1,$2)";
      $result1 = pg_query_params($pg_connect, $q2, array($email, $password_hash));

      $q3 = "select id_n from utente where email= $1";
      $result2 = pg_query_params($pg_connect, $q3, array($email));

      $tuple_id_n = pg_fetch_array($result2, null, PGSQL_ASSOC);

      $q4 = "insert into setutente(id_n, id_set) values ($1,NULL)";
      $result3 = pg_query_params($pg_connect, $q4, array($tuple_id_n["id_n"]));

      $q5 = "insert into missingparts(id_n, set_id, missing_parts) values ($1,NULL,NULL)";
      $result4 = pg_query_params($pg_connect, $q5, array($tuple_id_n["id_n"]));


      if ($result1) {
        echo "<span>La registrazione è andata a buon fine!</span>";

      } else {
        echo ("Registrazione fallita");
      }
    }
  } 
  ?>

</body>

</html>