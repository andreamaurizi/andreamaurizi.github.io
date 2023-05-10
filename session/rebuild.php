<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReBuild</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script>
    // accedi ai dati memorizzati nel localStorage

    function prova(){
        
        
        // invia i dati al server PHP utilizzando AJAX
        var parti = localStorage.getItem("myData");
        var part = JSON.parse(parti);
        
        $.ajax({
            type: 'POST',
            url: 'rebuild.php',
            data: { data: parti },
            success: function(response) {
                //console.log(response);
                console.log(parti);
            }
            
        });
    }

    </script>
</head>
<body>
    <form action='rebuild.php' method='POST'>
        <input type="submit" value="REBUILD" onclick="prova()">
    </form>
</body>
</html>

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

        $jsonString = $_POST["data"];
        $myArray = json_decode($jsonString, true);
        echo $myArray;
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