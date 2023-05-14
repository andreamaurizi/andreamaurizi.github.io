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
        $id_n = $_SESSION["user_id"];

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
            print_r( $everySetArray[$i]["set_id"]);
            echo ": ";
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
                echo "/";
                print_r( $partsArray[$j]["quantity"]);
                echo "----";*/
                
                $q3 = "select unnest(parts) from setutente where id_n = $id_n";


                $result3 = pg_query($pg_connect, $q3);
                //print_r(pg_fetch_array($result3)['0']);

                $prova = pg_fetch_all($result3);
                //  print_r($prova);
                $myPartsArray = array();
                //print_r( $prova);

                foreach($prova as $row){
                    $values = array_values($row);
                    $tupla = $values[0];
                    //$quantity = $values[1];
                    if($partsArray[$j]['part_id'] == $tupla[0]){
                        echo " SI";
                    }
                    else{
                        echo"NO";
                    }
                }




                /*while ($row = $prova) {
                // prepend each row to the beginning of the array
                    array_unshift($myPartsArray, array('part_id' => $row[0], 
                    'total_value' => $row[1]));
                }
                print_r( $myPartsArray);
                /*if(in_array($partsArray[$j], $myPartsArray)){
                    echo "SI";
                }
                else{
                    echo "NO";
                }*/
                // Creiamo un array con tutti i set
              
            }
            echo "<br>";

        }

        
    }


?>