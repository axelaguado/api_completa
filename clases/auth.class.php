<?php 
require_once "conexion/conexion.php";
require_once "respuesta.php";

class auth extends conexion {

    public function login($json){
        $_response = new respuesta;
        $datos = json_decode($json, true);
        
        //Basicamente tengo que trabajar con los datos de mi BDD.
        //En este caso, use en postman que mandaba campos user y password
        //Que es lo que obtengo en auth.php en file_get_content(php://imput)
        if (!isset($datos["user"]) || !isset($datos['password'])) {
            return $_response->error_400();
        }else {
            $user = $datos['user'];
            $password = $datos['password'];

            $datos = $this->obtenerDatosUsuario($user);
            
            //Verifico que se hayan cargado datos existentes
            if(is_array($datos)) {

                //Verificamos las contraseñas.
                if (password_verify($password, $datos[0]['contraseña'])) {
                    //Debemos crear el token.
                    $verificar = $this->insert_token($datos[0]['usuario_id']);

                    // Aca tengo un problema de condicionales nunca se devolvera codigo 500.
                    if(!is_int($verificar)) { 
                        $result = $_response->response;
                        $result['result'] = [
                            'token' => $verificar
                        ];
                        return $result;
                    } else {
                        return $_response->error_500('Error interno, no se pudo generar el token');
                    }
                } 
                //La contraseña no es igual.
                else {
                    return $_response->error_200("User and/or password not exist.");
                }

            //Devuelvo si no hay datos existentes.
            } else {
                return $_response->error_200("User and/or password not exist.");
            }
        
        }
    }

    private function obtenerDatosUsuario($user) {
        //En esta linea se utiliza el parametro que recibe la funcion.
        $query = "SELECT usuario_id, nombre, apellido, contraseña FROM usuarios WHERE email = '$user'"; //Una consulta que me permita traer los datos que me interesan del usario.
        
        //parent:: es utilizado para usar algun metodo de la clase de la cual nos estamos extendiendo o justamente heredando.
        $datos = parent::obtenerDatos($query);

        if(isset($datos['0']['usuario_id'])) {
            return $datos;
        } else {
            return 0;
        }
    }

    public function insert_token($usuarioId) {
        $val = true;  

        // Bin2Hex convierte a hexadecimal.
        // Openssl_random... genera una cadena de bbytes pseudo aleatoria. Ocurre una curiosa situacion si se pasa TRUE en vez de a variable.
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date('Y-m-d H:i:s');
        $estado = "ACTIVO";
        $query = "INSERT INTO usuario_token (usuario_id, token, estado, fecha) VALUES ( $usuarioId ,'$token', '$estado', '$date')";
        
        $verifica = parent::nonQuery($query);

        if ($verifica > 0) {
            return $token;
        } else {
            return $verifica;
        }
    }

}

?>