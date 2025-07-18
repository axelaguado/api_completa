<?php
require_once "conexion/conexion.php";
require_once "clases/respuesta.php";

class producto extends conexion {

    // Estamos modelando la tabla?.
    private $table = "producto";

    // Definimos los campos de la tabla. 
    private $productoId;
    private $marca = "";
    private $modelo = "";
    private $categoria;
    private $precio = 0;
    private $stock  = 0;
    private $descripcion = "";
    private $imagen = "";
    private $estado = 0; 
    private $token;

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

    // Implementamos metodo para validar los datos.
    public function post($json) 
    {
        $respuesta = new respuesta;
        
        // Como lo que recibimos es un json, primero lo decodificamos como array asociativo. 
        $datos = json_decode($json, true);

        // Verificamos que el token es enviado en la solicitud.
        if(!isset($datos['token']))
        {
            return $respuesta->error_401();
        }
        else 
        {
            // Seteamos y Verificamos la existencia del token. 
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if($arrayToken != 0)
            { 

                if(!isset($datos['productoId']) || !isset($datos['categoria'])) 
                {
                    return $respuesta->error_400();
                } 
                else
                {  
                    $this->productoId = $datos['productoId'];

                    if(isset($datos['marca'])){
                        $this->marca =  $datos['marca'];
                    }
            
                    if(isset($datos['modelo'])){
                        $this->modelo =  $datos['modelo'];
                    }
             
                    $this->categoria =  $datos['categoria'];

                    if(isset($datos['precio'])){
                        $this->precio =  $datos['precio'];
                    }
             
                    if(isset($datos['stock'] )){
                        $this->stock = $datos['stock']  ;
                    }

                    if(isset( $datos['descripcion'])){
                        $this->descripcion = $datos['descripcion'] ;
                    } 

                    // Ver que onda el tratamiento de imagenes.
                    if(isset($datos['imagen'] )){
                        $this->imagen =  $datos['imagen'];
                    }

                    if(isset($datos['estado'] )){
                        $this->estado =  $datos['estado'];  
                    } 

                    $response = $this->insertarProducto(); 

                    if ($response == -1) {
                        return  $respuesta->error_500(); 
                    } 
                    else 
                    {
                        $result = $respuesta->response;
                        $result['result'] = array(
                                'Producto' => 'El producto se registro correctamente [' . $response . ']' 
                            );
                        return $result;
                    }
                }  
            } 
            else
            {
                return $respuesta->error_401("El Token que envio es invalido o ha caducado");
            } 
        }
    }

    public function put($json) 
    {
        $respuesta = new respuesta;
        
        //Como lo que recibimos es un json, primero lo decodificamos como array asociativo. 
        $datos = json_decode($json, true);

        if(!isset($datos['token']))
        {
            return $respuesta->error_401();
        }
        else 
        {
            // Seteamos y Verificamos la existencia del token. 
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if($arrayToken != 0)
            { 
                if(!isset($datos['productoId']))  
                {
                    return $respuesta->error_400();
                } 
                else
                {
                    $this->productoId = $datos['productoId'];

                    if(isset($datos['marca'])){
                        $this->marca =  $datos['marca'];
                    }
            
                    if(isset($datos['modelo'])){
                        $this->modelo =  $datos['modelo'];
                    }
             
                    if(isset($datos['categoria'])){
                        $this->categoria =  $datos['categoria'];
                    } 

                    if(isset($datos['precio'])){
                        $this->precio =  $datos['precio'];
                    }
             
                    if(isset($datos['stock'] )){
                        $this->stock = $datos['stock']  ;
                    }

                    if(isset( $datos['descripcion'])){
                        $this->descripcion = $datos['descripcion'] ;
                    } 

                    // Ver que onda el tratamiento de imagenes.
                    if(isset($datos['imagen'] )){
                        $this->imagen =  $datos['imagen'];
                    }

                    if(isset($datos['estado'] )){
                        $this->estado =  $datos['estado'];  
                    } 

                    $response = $this->actualizarProducto();  
 
                    if ($response < 1) {
                        return  $respuesta->error_500(); 
                    } 
                    else 
                    {
                        $result = $respuesta->response;
                        $result['result'] = array(
                        'Producto' => 'El producto se actualizo correctamente [' . $response . ']' 
                        );
                        return $result;
                    }  
                }
            }
            else
            {
                return $respuesta->error_401("El Token que envio es invalido o ha caducado");
            } 
 
        }  
    }

    public function delete($json){
        $respuesta = new respuesta;

        $datosArray = json_decode($json, true);

        if(!isset($datosArray['token']))
        {
            return $respuesta->error_401();
        }
        else 
        {
            // Seteamos y Verificamos la existencia del token. 
            $this->token = $datosArray['token'];
            $arrayToken = $this->buscarToken();

            if($arrayToken != 0)
            { 
                if(!isset($datosArray['productoId'])){
                    return $respuesta->error_400();
                }
                else 
                {
                    $this->productoId = $datosArray['productoId'];

                    $response = $this->eliminarProducto(); 
             
                    if ($response < 1) {
                        return $respuesta->error_500();
                    } 
                    else 
                    {
                        $result = $respuesta->response;
                        $result['result'] = array( 
                        'Producto' => 'El producto se dio de baja correctamente. ['. $response . ']' 
                        );   
                        return $result;
                    }   
                } 
            }
            else
            {
                return $respuesta->error_401("El Token que envio es invalido o ha caducado");
            }
        }  
    }


    // Implementamos metodo que ejecutar la insercion.
    private function insertarProducto()
    {
        // Ya hemos verificado que la query se forme como corresponde.
        $query = "INSERT INTO " . $this->table . " (productoId, marca, modelo, categoria, precio, stock,
        descripcion, imagen, estado) VALUES (" . $this->productoId . ", '" . $this->marca . "', 
        '" . $this->modelo . "', " . $this->categoria . ", " . $this->precio . ", " . $this->stock . ", '"
        . $this->descripcion . "', '" . $this->imagen . "', " . $this->estado . ")"; 

        $verificar = parent::nonQuery($query); 
 
        return $verificar;   
    } 

    // Implementamos metodo para ejecutar la actualizacion.
    private function actualizarProducto()
    {
        // Ya hemos verificado que la query se forme como corresponde. 
        $query = "UPDATE " . $this->table . " SET marca = '" .  $this->marca . "', modelo = '" . $this->modelo . "', categoria = " . $this->categoria 
        . ", precio = " . $this->precio . ", stock = " . $this->stock . ", descripcion = '" . $this->descripcion . "', imagen = '" . $this->imagen 
        . "', estado = " . $this->estado . " WHERE productoId = " . $this->productoId;
 
        $verificar = parent::nonQuery($query);  
        
        return $verificar;   
    } 

    // Implementamos metodo para ejecutar la eliminacion. En este caso es una baja logica.
    private function eliminarProducto() 
    {
        $query = "UPDATE " . $this->table . " SET estado = 0 WHERE productoId = " . $this->productoId;

        $verificar = parent::nonQuery($query);

        return $verificar;
    }

    // Verificamos la existencia del token enviado en la solicitud.
    private function buscarToken(){
        $query = "SELECT  token_id, usuario_id, estado from usuario_token WHERE token = '" . $this->token . "' AND estado = 'ACTIVO'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    // Cada vez que hacemos una solicitud se debeb actualizar el token para saber que sigue activo.    
    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuario_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }
}
?>