<?php

    function conecta() {
        $host = 'localhost';
        $port = '5434';
        $dbname = 'proyecto';
        $user = 'postgres';
        $password = 'Pacorro522';
    
        $connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $dbconn = pg_connect($connection_string);
    
        if (!$dbconn) {
            echo 'Error al conectar a la base de datos';
            exit();
        }
    
        return $dbconn;
    }
?>
    