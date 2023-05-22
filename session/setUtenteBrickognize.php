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
        // parte di brickognize
        if (isset($_POST['set'])) {
            $set = $_POST['set'];
          

          }
        $id_n = $_SESSION["user_id"];
        $q1 = "select * from parts where set_id= $1";
        $result = pg_query_params($pg_connect, $q1, array($set));
        // Controllo che il lego set esista
        if (!($tuple=pg_fetch_array($result, null, PGSQL_ASSOC))) {
            die("Lego set inesistente");
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
                    echo "Set gia presente";
                    die();
                } else {
                    echo "Lego set aggiunto";
                
                    $newElements = array($set);

                    // Operazione di traduzione
                    $newElementsString = "'" . implode("', '", $newElements) . "'";
                    $sql = "UPDATE setutente SET id_set = id_set || ARRAY[$newElementsString] WHERE id_n = $id_n";
                    $result = pg_query($pg_connect, $sql);

                    // Controllo errori
                
                    // Debugging
                    $q3 = "select * from setutente, UNNEST(id_set) as set_id where id_n= $1";
                    $result3 = pg_query_params($pg_connect, $q3, array($_SESSION["user_id"]));
                   
                    $mySetArray = array();
                    while ($row = pg_fetch_assoc($result3)) {
                        // Aggiungiamo ogni riga all'inizio dell'array
                            array_unshift($mySetArray, array('set_id' => $row['set_id']));
                        }

                    
                    $js_set = json_encode($mySetArray);
                    //echo $js_set;
                   


                    //seleziona tutte le parti che possiede l'utente
                    
                    $query = "SELECT b.part_id, sum(b.quantity) as total_value
                    FROM (
                          SELECT legoset
                        FROM setutente, UNNEST(id_set) AS legoset
                        where id_n=$id_n
                    ) AS t
                    JOIN parts b ON t.legoset = b.set_id
                             
                    group by b.part_id
                    order by total_value desc";

                    $result = pg_query($pg_connect, $query);

                   
                
                    $data = array(); 

                    while ($row = pg_fetch_assoc($result)) {
                    // Aggiungiamo ogni riga all'inizio dell'array
                        array_unshift($data, array('part_id' => $row['part_id'], 'total_value' => $row['total_value']));
                    }
                    

                    $js_data = json_encode($data);
                    $query5 = "UPDATE setutente
                    SET parts = (
                    SELECT ARRAY_AGG(ROW(part_id, total_value)) AS parts
                      FROM (
                        SELECT b.part_id, SUM(b.quantity) AS total_value
                        FROM (
                          SELECT legoset
                          FROM setutente, UNNEST(id_set) AS legoset
                          WHERE id_n = $id_n
                        ) AS t
                        JOIN parts b ON t.legoset = b.set_id
                        GROUP BY b.part_id
                        ORDER BY total_value DESC
                      ) AS q
                    ) where id_n = $id_n";
                                        
                    $result5 = pg_query($pg_connect, $query5);

    
                    echo "<html><body>";
                    echo "<script>";
                    echo "localStorage.setItem('myData', '$js_data');";
                    echo "localStorage.setItem('mySet', '$js_set');";
                    echo"setTimeout(function(){
                        window.location.href=
                        \"./session.php\";
                        }, 5000000)";
                    echo "</script>";
                    echo "</body></html>";

                    
                }
            }

        }
        
    }

?>