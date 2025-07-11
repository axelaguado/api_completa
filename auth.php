<?php
    require_once "clases/auth.class.php";
    require_once "clases/respuesta.php";

    $_auth = new auth;
    $_response = new respuesta;

    // Preguntamos si el metodo solicitado es post. Por que?
    // Solo enviaremos la contraseña/token mediante este metodo, si los hacemos con metodos GET se corre riesgo
    // de que el token pueda ser interceptado por un tercero. 
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        // Obtengo los datos que envie mediante el metodo POST.
        // Para ellos utilizo al flujo "php://input".
        // Que nos permite acceder a los datos sin procesar enviados en el cuerpo de la peticion HTTP. 
        $responseBody = file_get_contents("php://input");
        
        // Enviamos los datos para el login.
        $datosArray = $_auth->login($responseBody);
        
        // Devolvemos una respuesta.
        header('Content-type: application/json');
        if (isset($datosArray['result']['error_id'])) {
            $responseCode = $datosArray['result']['error_id'];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        
        echo json_encode($datosArray);

    } else {
        // Devolvemos el error porque el metodo no esta disponible.
        header('Content-type: application/json');
        $datosArray = $_response->error_405();
        echo json_encode($datosArray);
    }

?>