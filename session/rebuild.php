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

        $q3 = "SELECT split_part(Unnest(parts), ',', 1) 
                AS part_id, split_part(Unnest(parts), ',', 2) 
                AS quantity FROM setutente where id_n = $id_n";


        $result3 = pg_query($pg_connect, $q3);
        //print_r(pg_fetch_array($result3)['0']);

        $prova = pg_fetch_all($result3);
        $myPartsArray = array();

        foreach($prova as $row){
            $parts = str_replace('(','',$row['part_id']);
            $quantity = str_replace(')','',$row['quantity']);
            array_unshift($myPartsArray,array('part_id' => $parts, 
            'quantity' => $quantity));
        }
       
        $myParts_id = array();
        $myPartsQuantity = array();

        // iterate through each row of the matrix
        foreach ($myPartsArray as $row) {
            // access the desired column by its index and append its value to the column_values array
            $myParts_id[] = $row["part_id"];
        }
        foreach ($myPartsArray as $row) {
            // access the desired column by its index and append its value to the column_values array
            $myPartsQuantity[] = $row["quantity"];
        }



        $q4 = "select id_set from setutente where id_n = $id_n";
        $result4 = pg_query($pg_connect, $q4);
        $mieiSetStringa = pg_fetch_all($result4);
        $stringa = $mieiSetStringa[0]["id_set"];
        $stringa = str_replace(array('{', '}'), '', $stringa); // Rimuovi le parentesi graffe
        $mieiSet = explode(',', $stringa); // Separa la stringa in un array
        




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
        for ($i = 0; $i < count($everySetArray)/20; $i++) {
            if(in_array($everySetArray[$i]["set_id"], $mieiSet)){  
                continue;
                }
            $setStringa = $everySetArray[$i]["set_id"];
            print_r( $setStringa);
            echo "<br>";
            $q2 = "select part_id, quantity
                   from parts
                   where set_id=$1";
            $result2 = pg_query_params($pg_connect, $q2, array($setStringa) );

            // Creiamo un array con tutte le parti di un set
            $partsArray = array();
            while ($row = pg_fetch_assoc($result2)) {
                array_unshift($partsArray, array('part_id' => $row['part_id'], 
                'quantity' => $row['quantity']));
            }
            // Scandiamo le parti per ogni set
            for ($j = 0; $j < count($partsArray); $j++) {

                $partStringa =$partsArray[$j]["part_id"];
                $quantityStringa =$partsArray[$j]["quantity"];
                //parte del set compare nelle parti utente
                 if(in_array($partStringa, $myParts_id)){
                //IF Differenza quantità
                    echo $partStringa."SI <br>";

                }
                // Caso in cui parte del set non compare nelle parti dell'utente
                else{

                    //controllo se il set è nel db missingparts
                    $q5 = "select * from missingparts where set_id=$1 AND id_n=$id_n";
                    $result5= pg_query_params($pg_connect,$q5,array($setStringa));

                    //set presente nel db missingparts per l'utente
                    if($tuple=pg_fetch_array($result5)){
                        $q7 = "UPDATE missingparts
                        SET missing_parts = missing_parts || hstore($1, $2)
                        WHERE id_n = $id_n AND set_id = $3";
                        $result7 = pg_query_params($pg_connect, $q7, array($partStringa, $quantityStringa, $setStringa));
                        echo"esiste <br> ";
                    }
                    //set non presente nel db missingparts per l'utente
                    else{

                        //aggiungi il set
                        $q6= "insert into missingparts(id_n, set_id, missing_parts) values ($id_n,$1,hstore($2, $3))"; 
                        $result6 = pg_query_params($pg_connect, $q6, array($setStringa, $partStringa, $quantityStringa));
                        echo "creato <br>";
                    }
                    
                }
            }
        }
    }




?>

