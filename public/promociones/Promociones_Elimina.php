<?php
require "D:/DDD/htdocs/Proyecto/administrador/funciones/conecta.php";
$con = conecta();

$id = $_POST['id'];

$sql = "UPDATE promociones SET ELIMINADO = 1 WHERE ID = $id";
if ($con->query($sql) === TRUE) {
    echo 1;
} else {
    echo 0;
}
?>