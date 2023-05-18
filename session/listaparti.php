<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: /session.php");
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

session_start();

if($pg_connect){
    $setId = $_POST['setId'];
    $q1 = 'select part_id, quantity from parts where set_id = $1';
    $result1 = pg_query_params($pg_connect, $q1, array($setId));
    $arrayProva = array();
    while($tuple= pg_fetch_assoc($result1)){
        array_unshift($arrayProva, array('part_id' => $tuple['part_id'], 'quantity' => $tuple['quantity']));
    }
    $prova = json_encode($arrayProva);
    print_r($prova);
}


?>