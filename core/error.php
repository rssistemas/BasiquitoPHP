<?php
//-------------------------------------------------------------------------------------------------------------------------------------------------------
//Autor:Rafael Perez
//Comment:Extending class of exception, the message is personalized and the error log is created
//parameters: 
//--------------------------------------------------------------------------------------------------------------------------------------
class error extends Exception
{
        
    public function __construct() {
        
        parent::__construct(); 
    }
    
    
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
    //-------------------------------------------------------------------------
    //method that registers error in log
    //-------------------------------------------------------------------------
    private function errorLog($mensaje)
    {
        if(is_writable(LOG_PATH."log.txt"))
            {
         
               if($log = fopen(LOG_PATH."logError.txt","a+"))
               {
                   if(!empty($mensaje))
                   {
                      fwrite($log, date("F j, Y, g:i a").'  '.$mensaje. chr(13));
                   }    
                   fclose($log);
                   return TRUE;
               }
            }
        
    } 
    //-------------------------------------------------------------------------
    //method that generates the error message and shows it
    //-------------------------------------------------------------------------
    public function errorMessage() {
        // Mensaje de error
        $m="";
        
        
        $errorMsg = 'Error en la línea '
        .$this->getLine().' en el archivo '
        .$this->getFile() .': <b>'
        .$this->getMessage().
        '</b> ';
        
        $this->errorLog($errorMsg);
        
        $m=  $m."<div class='alert alert-warning alert-dismissible' role='alert'>";
        $m = $m ."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
        $m = $m ."<strong> ! </strong> " .$error." </div>";
        
        return $m;
    }
    
}        
?>