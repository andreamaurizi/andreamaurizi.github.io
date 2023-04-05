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
    $query1= "INSERT INTO user(email, password_hash) VALUES ('$email', '$password_hash')"; 
    var_dump($query1);
    $result1 = $pg_connect->pg_($query1);
    $result1->execute();
    print_r($_POST);
    var_dump($password_hash);

?>