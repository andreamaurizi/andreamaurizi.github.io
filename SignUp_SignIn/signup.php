<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
}
else {
    $host = "rebuild-signup-db.cir4pq5hlxfs.eu-north-1.rds.amazonaws.com";
    $dbname = "ReBuild_db";
    $username = "postgres";
    $port = "5432";
    $password = "rebuild1";
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

   if(! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        die("Inserire una mail valida");
    }   

   if(strlen($_POST["password"])<8){
        die("La password deve contenere almeno 8 caratteri");
    }

   if(!preg_match("/[a-z]/i", $_POST["password"])){
        die("La password deve contenere almeno una lettera");
    }

   if(!preg_match("/[0-9]/", $_POST["password"])){
        die("La password deve contenere almeno un numero");
    }

    if($_POST["password"] !== $_POST["conferma_password"]){
        die("La password deve coincidere");
    }
    if($pg_connect){
        $email = $_POST["email"];
        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        

        $email_escaped = pg_escape_string($email);
        $q1="select * from utente where email= $1";
        $result=pg_query_params($pg_connect, $q1, array($email));
        if ($tuple=pg_fetch_array($result, null, PGSQL_ASSOC)) {
             echo "<h1> Spiacente, l'indirizzo email non e' disponibile</h1>
                     Se vuoi, <a href=../login> clicca qui per loggarti </a>";
        }
        else{
            $q2= "insert into utente(email, password_hash) values ($1,$2)"; 
            $result1 = pg_query_params($pg_connect, $q2, array($email, $password_hash));

            if($result1){
                echo("La registrazione è andata a buon fine! Clicca <a href=login.html>qui</a> per loggarti");
            }
            else{
                echo("Registrazione fallita");
            }
        }
    }
?>

</body>
</html>