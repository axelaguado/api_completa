<?php
require_once "clases/producto.class.php";
require_once "clases/respuesta.php";

$_producto = new producto();
$_response = new respuesta();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['page'])){
            $pagina = $_GET['page'];
            $datosArray = $_producto->listarProductos($pagina);
            echo json_encode($datosArray);
        } else if(isset($_GET['id'])){
            $productoId = $_GET['id'];
            $datosArray = $_producto->obtenerProducto($productoId);
            echo json_encode($datosArray);
        }

    } else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo 'hola POST';
    } else if($_SERVER['REQUEST_METHOD'] == 'PUT') {
        echo 'hola PUT';
    } else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        echo 'hola DELETE';
    } else {
        header('Content-type: application/json');
        $datosArray = $_response->error_405();
        echo json_encode($datosArray);
    }
?>