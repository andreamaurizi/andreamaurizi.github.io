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

        // iterate through each row of the matrix
        foreach ($myPartsArray as $row) {
            // access the desired column by its index and append its value to the column_values array
            $myParts_id[] = $row["part_id"];
        }
        print_r($myParts_id);
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
            // Scandiamo le parti per ogni utente
            // Scandiamo le parti per ogni set
            for ($j = 0; $j < count($partsArray); $j++) {
                 if(in_array($partsArray[$j]["part_id"], $myParts_id)){

                    echo $partsArray[$j]["part_id"]."SI <br>";

                }
                else{
                    echo "NO <br>";
                }
            }
        }
    }




?>

