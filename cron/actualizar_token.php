<?php
    // Lo que tenemos aca, basicamente, es el script php, que realiza la actulizacion del estado de los token.

    // Se pueden programar(definir cada cuanto se ejecutara) y administrar a través de la línea de comandos 
    // o utilizando interfaces como cPanel o plugins en plataformas como WordPress. 

    require_once '../clases/token.class.php';

    $token = new token();
    $fecha = date('Y-m-d H:i:s');
 
    echo ' Se actualizaron ' . $token->actualizar_token($fecha) . ' token/s'; 
?>