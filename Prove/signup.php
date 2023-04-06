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
    $email = $_POST["email"];
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $pg_connect = require __DIR__ ."/database.php";

    $email_escaped = pg_escape_string($email);
    $query = "SELECT * FROM utente where email='$email_escaped'";
    $result = pg_query_params($pg_connect->get_resources(), $query);
    if(pg_num_rows($result) > 0){
        echo("Indirizzo email gia in uso. Se sei registrato clicca <a href>qui</a> per loggarti");
    }
    else{
        $query1= "INSERT INTO utente(email, password_hash) VALUES ($1,$2)"; 
        $result1 = pg_query_params($pg_connect, $query1, array($email, $password_hash));

        if($result1){
            echo("La registrazione è andata a buon fine! Clicca <a href>qui</a> per loggarti");
        }
        else("Registrazione fallita");
    }
    print_r(gettype($result));
    print_r($_POST);
    var_dump($password_hash);

?>