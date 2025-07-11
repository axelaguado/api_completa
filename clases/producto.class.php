<?php
require_once "conexion/conexion.php";
require_once "clases/respuesta.php";

class producto extends conexion {
    private $table = "producto";

    public function listarProductos($pagina = 1) {
        $inicio = 0; // fila en la que arranca.
        $cantidad = 100; //cantidad de elementos que queremos recuperar.

        //Suponiendo que $pagina = 2.
        if($pagina > 1) {

            // $incio = (100 * (2 - 1)) + 1
            // $incio = (100 * 1) + 1
            // $inicio = 101
            $inicio = ($cantidad * ($pagina - 1)) + 1;
           
            // $cantidad = 100 * 2.
            // $cantidad = 200
            $cantidad = $cantidad * $pagina;
        }

        $query = "SELECT productoId, marca, modelo, stock, precio FROM " . $this->table . " LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

    public function obtenerProducto( $productoId) {
        $query = "SELECT * FROM " . $this->table . " WHERE productoId = '$productoId'";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

}
?>