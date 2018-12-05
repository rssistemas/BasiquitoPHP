<?php
class error
{
    public static function alerta($codigo,$url)
    {
        header("location:". BASE_URL ."error/alerta/".$codigo.'/'.$url ); 
        //exit;
    }
    public static function confirmacion($destino,$codigo = false)
    {
        header("location:". BASE_URL ."error/alerta/".$destino.'/'.$codigo ); 
        exit;
    }
}        
?>