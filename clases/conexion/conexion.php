<?php 
class conexion {
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    /* Desde mi punto de vista la conexion no se deberia realizar en el constructor sino que en una funcion aparte, 
    aunque para este caso permite que la conexion se de al instanciar la clase y no llamando a una funcion para que lo haga.
    */
    function __construct(){
        $listaDatos = $this->datosConexion();

        /* 
            Se recorre el json de config como array y como tiene una sola clave cuyo valor es otro array, value llamara
            a cada una de sus claves. 
        */
        foreach ($listaDatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        
        /*  Diferencias connect_...
            connect_error = Devuelve una cadena con la descripción del último error de conexión.
            connect_errno =  Devuelve el código de error de la última llamada de conexión.
        */
        if($this->conexion->connect_errno) {
            echo $this->conexion->connect_error; 
        } else {
            echo "Conexion exitosa";
        }
    }

    private function datosConexion() {
        /*
          La funcion dirname() devuelve la ruta del directorio principal.
          Para este caso: C:\xampp\htdocs\api_completa\clases\conexion

          La constante __FILE__ devuelve la ruta completa de este archivo.
          Para este caso: C:\xampp\htdocs\api_completa\clases\conexion\conexion.php
        */
        $direccion = dirname(__FILE__);
        $jsonData = file_get_contents($direccion . "/" . "config");
        
        return json_decode($jsonData, true);
    }


    // El objetivo de este metodo es convertir en formato UTF-8 los datos que obtiene
    // de la base de datos. (Por si nos encontramos con caracteres como tildes o ñ's). 
    private function convertirUTF8($array) {
    /*
        La funcion array_walk_recursive() Aplica la función definida por el usuario callback a cada elemento del array. 
        Esta función opera de forma recursiva sobre arrays con más niveles.
        Las claves y valores de la matriz son parametros de la funcion callback.

        Como podemos observar el primer parametro de la funcion callback es pasado por referencia.
        De esta manera, trabajamos con los valores reales del array. 
    */
        array_walk_recursive($array, function(&$value, $key){

            /* mb_detect_encoding: detecta la codificacion de caracteres.*/
                if(!mb_detect_encoding($value, 'utf-8', true)){
                    $value = utf8_encode($value);
                }
            }
        );
        return $array;
    }
     
    // Aca, se supone realizamos una consulta para lectura (Metodo READ).
    public function obtenerDatos($query){
        $results = $this->conexion->query($query);
        $resultsArray = array();
        foreach($results as $item) {
            $resultsArray[] = $item;
        }
        return $this->convertirUTF8($resultsArray);
    }

    // Los metodo nonQuery se denominan de esta forma porque la consulta no es para lectura.
    // Se utilizan para create, delete, update. 
    public function nonQuery($query) {
        $results = $this->conexion->query($query);
        return $this->conexion->affected_rows; //affected_rows obtiene el numero de filas afectadas en la ultima operacion.
    }
 
    // Metodo nonQuery con manejo de errores, ademas devuelve el numero de filas afectadas por la consulta.
    public function nonQueryWithError($query) {
        if ($this->conexion->query($query)) {
            return $this->conexion->affected_rows;
        } else {
            echo "Error al ejecutar la consulta: " . $this->conexion->error;
            return -1; // o cualquier otro valor que indique un error
        }
    }


    // INSERT
    // Este metodo si bien puede usarse para otras operaciones distintas a create, tiene como diferencia el retorno
    // de un valor que seria el id insertado.
    public function nonQueryId($query) {
        $this->conexion->query($query);
        $filas = $this->conexion->affected_rows;
        if($filas >= 1) {
            return $this->conexion->insert_id;
        } else {
            return 0;
        }
    }
}
?>