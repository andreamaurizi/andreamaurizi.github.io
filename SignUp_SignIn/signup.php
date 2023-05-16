<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/session.css" />
</head>
<body>
<nav class="navbar">
      <div class="navbar__container">
        <a href="/" id="navbar__logo"
          ><img src="/Img/lego-icon-12.ico" id="logo" /> ReBuild</a
        >
        <div class="navbar__toggle" id="mobile-menu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
          <li class="navbar__item">
            <a href="/" class="navbar__links">Home</a>
          </li>
          <li class="navbar__item">
            <a href="/tech.html" class="navbar__links">Tech</a>
          </li>
          <li class="navbar__item">
            <a href="/" class="navbar__links">Products</a>
          </li>
          <li class="navbar__btn">
            <a href="/SignUp_SignIn/Login_Registration.html" class="button">Area Riservata</a>
          </li>
        </ul>
      </div>
    </nav>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
}
else {
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
                    Aspetta 5 secondi per essere reindirizzato alla pagina di login
                    oppure <a href=./Login_Registration.html> clicca qui</a>";
            echo"<script> 
                     setTimeout(function(){
                     window.location.href=
                      \"./Login_Registration.html\";
                     }, 5000);
                 </script>";
        }
        else{
            $q2= "insert into utente(email, password_hash) values ($1,$2)"; 
            $result1 = pg_query_params($pg_connect, $q2, array($email, $password_hash));

            $q3="select id_n from utente where email= $1";
            $result2=pg_query_params($pg_connect, $q3, array($email));

            $tuple_id_n=pg_fetch_array($result2, null, PGSQL_ASSOC);

            $q4= "insert into setutente(id_n, id_set) values ($1,NULL)"; 
            $result3 = pg_query_params($pg_connect, $q4, array($tuple_id_n["id_n"]));

            $q5= "insert into missingparts(id_n, set_id, missing_parts) values ($1,NULL,NULL)"; 
            $result4 = pg_query_params($pg_connect, $q5, array($tuple_id_n["id_n"]));


            if($result1){
                echo("<h1>La registrazione è andata a buon fine!</h1><br> 
                Aspetta 5 secondi per essere reindirizzato alla pagina di login
                oppure clicca <a href=./Login_Registration.html>qui</a>");
                echo("<script> 
                        setTimeout(function(){
                        window.location.href=
                         \"./Login_Registration.html\";
                        }, 5000);
                    </script>");
            }   
            else{
                echo("Registrazione fallita");
            }
        }
    }
?>

</body>
</html>