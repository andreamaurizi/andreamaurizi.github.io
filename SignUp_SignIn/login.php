<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /");
}
else {
    $host = "localhost";
    $dbname = "ReBuild";
    $username = "postgres";
    $port = "5432";
    $password = "postgres";
    $conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";


    $pg_connect = pg_connect($conn_string)
                or die('Could not connect: ' . pg_last_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
<body>
<?php
    if($pg_connect){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $q1 = "select * from utente where email= $1";
        $result = pg_query_params($pg_connect, $q1, array($email));
        if (!($tuple=pg_fetch_array($result, null, PGSQL_ASSOC))) {
            echo "<span>Non sembra che ti sia registrato</span>";
        }
        else{
            $q2 = "select * from utente where email = $1";
            $result = pg_query_params($pg_connect, $q2, array($email));
            $tuple=pg_fetch_array($result, null, PGSQL_ASSOC);
            if(password_verify($password, $tuple['password_hash'])){
                        print_r("Benvenuto");
                        session_start();
                        $_SESSION["user_id"] = $tuple['id_n'];
                        exit;
            }
            else{
                echo "<span> La password e' sbagliata! </span>";
            }
        }
    }
?>
</body>