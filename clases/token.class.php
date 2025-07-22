<?php
require_once 'conexion/conexion.php';
    
class token extends conexion 
{
    public function actualizar_token($fecha)
    {
        $query = "UPDATE usuario_token SET estado = 'INACTIVO' WHERE fecha < '$fecha' AND estado = 'ACTIVO' ";  
        $verificar = parent::nonQuery($query);

        if ($verificar > 0) {
            return $verificar; // $this->escribirEntrada($verificar)
        }
        else {
            return 0;
        } 
    }

    // Si quisieramos que, a la hora de realizar una actualizacion del estado de los mismos, se lleve un registro en
    // un archivo.
    
    /*

    //Recibe la cantidad de filas afectada a la hora de ejecutarse la actualizacion.
    function escribirEntrada($registros){
        $direccion = "../cron/registros/registros.txt";
        if(!file_exists($direccion)){
            $this->crearArchivo($direccion);
        }
        //crear una entrada nueva
        $this->escribirTxt($direccion, $registros);
    }

    function crearArchivo($direccion){
        $archivo = fopen($direccion, 'w') or die ("error al crear el archivo de registros");
        $texto = "------------------------------------ Registros del CRON JOB ------------------------------------ \n";
        fwrite($archivo,$texto) or die ("no pudimos escribir el registro");
        fclose($archivo);
    } 

    function escribirTxt($direccion, $registros){
        $date = date("Y-m-d H:i");
        $archivo = fopen($direccion, 'a') or die ("error al abrir el archivo de registros");
        $texto = "Se modificaron $registros registro(s) el dia [$date] \n";
        fwrite($archivo,$texto) or die ("no pudimos escribir el registro");
        fclose($archivo);
    }
    */
}


?>