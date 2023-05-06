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

    if ($pg_connect) {
        // controllo che il numero di set sia sintatticamente corretto
        if(!preg_match("/^\d{1,5}-\d$/", $_POST["set"])){
            die("Il numero di set inserito è invalido");
        }
        $set = $_POST["set"];
        $id_n = $_SESSION["user_id"];
        $q1 = "select * from parts where set_id= $1";
        $result = pg_query_params($pg_connect, $q1, array($set));
        // Controllo che il lego set esista
        if (!($tuple=pg_fetch_array($result, null, PGSQL_ASSOC))) {
            echo "Lego set inesistente";
        } else {     
            $q2 = "select * from setutente where id_n= $1";
            $result2 = pg_query_params($pg_connect, $q2, array($id_n));
            if (!($tuple2=pg_fetch_array($result2, null, PGSQL_ASSOC))) {
                echo "L'utente non risulta registrato";
            } else{
                // Convertiamo l'array in un php array
                $phpArray = explode(",", substr($tuple2["id_set"], 1, -1));

                // Controlla se l'elemento è presente
                if (in_array($set, $phpArray)) {
                    echo "The element '$set' is present in the array. e non va bene";
                    die();
                } else {
                    echo "The element '$set' is not present in the array.";
                
                    $newElements = array($set);

                    // Operazione di traduzione
                    $newElementsString = "'" . implode("', '", $newElements) . "'";
                    $sql = "UPDATE setutente SET id_set = id_set || ARRAY[$newElementsString] WHERE id_n = $id_n";
                    $result = pg_query($pg_connect, $sql);

                    // Controllo errori
                    if (!$result) {
                        echo "Error adding elements to array: " . pg_last_error($pg_connect);
                    } else {
                        echo "Elements added to array successfully.";
                    }
                    // Debugging
                    $q3 = "select * from setutente where id_n= $1";
                    $result3 = pg_query_params($pg_connect, $q3, array($_SESSION["user_id"]));
                    $tuple3=pg_fetch_array($result3, null, PGSQL_ASSOC);
                    print_r($tuple3["id_set"]);
                }
            }

        }
        
    }

?>

