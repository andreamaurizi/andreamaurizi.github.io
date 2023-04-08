<?php
#DB CONTEXT
# host=rebuild-signup-db.cir4pq5hlxfs.eu-north-1.rds.amazonaws.com
# port = 5432
# dbname = ReBuild_db
# username = postgres
# password = rebuild1

$host = "rebuild-signup-db.cir4pq5hlxfs.eu-north-1.rds.amazonaws.com";
$dbname = "ReBuild_db";
$username = "postgres";
$port = "5432";
$password = "rebuild1";
$conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";


$pg_connect = pg_connect($conn_string)
            or die("Errore di connessione: ". pg_last_error(). "<br/>");


return $pg_connect;

?>

