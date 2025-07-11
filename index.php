<?php 
    /*
        include = se utiliza para incrustar código PHP desde otro archivo. 
            Si no se encuentra el archivo, se muestra una advertencia y el programa continúa ejecutándose.

        include_once = se utiliza para incrustar código PHP desde otro archivo. 
            Si no se encuentra el archivo, se muestra una advertencia y el programa continúa ejecutándose. 
            Si el archivo ya estaba incluido anteriormente, esta declaración no lo volverá a incluir.

        require = se utiliza para incrustar código PHP desde otro archivo. 
            Si no se encuentra el archivo, se produce un error fatal y el programa se detiene.

        require_once = se utiliza para incrustar código PHP desde otro archivo. 
            Si no se encuentra el archivo, se produce un error fatal y el programa se detiene. 
            Si el archivo ya estaba incluido anteriormente, esta declaración no lo volverá a incluir.

        Diferencias require's = Rendimiento, requiere_once debe comrpobar que no se este intentando incluir un archivo
        mas de una vez.
    */
    require_once 'clases/conexion/conexion.php';
    require_once 'clases/auth.class.php';
    echo "Soy el index";
    // Primer mensaje "conexion exitosa"
    $_conexion = new conexion(); 

    $datos = [
        'user' => 'axelaguado@gmail.com',
        'password' => 'administrador123'
    ];
    $json = json_encode($datos);

    //$_auth = new auth();
    //print_r(  $resultadoLogin = $_auth->login($json));

    /*
    $val = true;
    $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
    $estado = 'ACTIVO';
    $date = date('Y-m-d H:i:s');
    $query = "INSERT INTO usuario_token (usuario_id, token, estado, fecha) VALUES ( 3 ,'$token', '$estado', '$date')";
    
    // Inserta un token, por el momento devuelve -1 (error)  
    $verifica = $_conexion->nonQuery($query);
    echo $verifica; 
    echo "</br>";

    // Este new auth devuelve un conexion exitosa? SI
    $_auth = new auth();
     
    // Evidentemente no se inserta el token y por lo tanto verifica retorna -1.
    $verifica = $_auth->insert_token(3);
    echo $verifica;
    */
    
    // Tengo mal la hora y fecha xd
    echo ( $date = date('Y-m-d H:i:s'));

?>