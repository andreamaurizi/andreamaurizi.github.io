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

        
       echo $_SESSION["mySet"];

        // Query per selezionare tutti i set nel database
        $q1 = "select distinct set_id
        from parts";
        $result1 = pg_query($pg_connect, $q1);

        // Creiamo un array con tutti i set
        $everySetArray = array();
        while ($row = pg_fetch_assoc($result1)) {
        // prepend each row to the beginning of the array
            array_unshift($everySetArray, array('set_id' => $row['set_id']));
        }
        
        // Scandiamo ogni set
        for ($i = 0; $i < count($everySetArray); $i++) {
            //print_r( $everySetArray[$i]["set_id"]);
            //echo " ";
            $q2 = "select part_id, quantity
            from parts
            where set_id=$1";
            $result2 = pg_query_params($pg_connect, $q2, array($everySetArray[$i]["set_id"]) );

            // Creiamo un array con tutte le parti di un set
            $partsArray = array();
            while ($row = pg_fetch_assoc($result2)) {
                array_unshift($partsArray, array('part_id' => $row['part_id'], 
                'quantity' => $row['quantity']));
            }
            // Scandiamo le parti per ogni set
            for ($j = 0; $j < count($partsArray); $j++) {
                /*print_r( $partsArray[$j]["part_id"]);
                echo "  ";
                print_r( $partsArray[$j]["quantity"]);
                echo "/";*/
            }
            //echo "<br>";

        }

        
    }


?>