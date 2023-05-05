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
        if(!preg_match("/^\d{1,5}-\d$/", $_POST["set"])){
            die("Il numero di set inserito è invalido");
        }
        $set = $_POST["set"];
        $q1 = "select * from parts where set_id= $1";
        $result = pg_query_params($pg_connect, $q1, array($set));
        if (!($tuple=pg_fetch_array($result, null, PGSQL_ASSOC))) {
            echo "Lego set inesistente";
        } else {
            $q2 = "select * from setutente where id_n= $1";
            $result2 = pg_query_params($pg_connect, $q2, array($_SESSION["user_id"]));
            if (!($tuple2=pg_fetch_array($result2, null, PGSQL_ASSOC))) {
                echo "L'utente non risulta registrato";
            } else{
                // Define the array of elements to add
                $newElements = array($set);

                // Escape and format the array elements for use in the SQL statement
                $newElementsString = "'" . implode("', '", $newElements) . "'";

                // Build the SQL statement to add the new elements to the array column
                $sql = "UPDATE setutente SET id_set = id_set || ARRAY[$newElementsString] WHERE id_n = 2";


                // Execute the SQL statement
                $result = pg_query($pg_connect, $sql);

                // Check for errors and display a message
                if (!$result) {
                echo "Error adding elements to array: " . pg_last_error($pg_connect);
                } else {
                echo "Elements added to array successfully.";
                }
                //$setArray = $tuple2["id_set"];
                //$setArray = array_values($tuple2["id_set"]);
                //print_r($setArray);
                //array_unshift($setArray, $set);
                //$q3= "insert into setutente(id_set) values ($1) where id_n= $2 "; 
                //$result3 = pg_query_params($pg_connect, $q3, array($setArray, $_SESSION["user_id"] ));
                //$tuple3=pg_fetch_array($result3, null, PGSQL_ASSOC);
                //print_r($tuple3);
            }

        }
        
    }

?>

