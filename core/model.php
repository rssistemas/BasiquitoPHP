<?php
// clase modelo utilizada para crear los modelos de datos   
class model
{
    private $_registry;	
        protected $_db;

        public function __construct()
        {
                $this->_registry = registry::getInstancia();
                $this->_db = $this->_registry->_db;			
        }
        public function iniciar()
        {
           $this->_db->beginTransaction(); 
        }        
        public function confirmar()
        {
            $this->_db->commit();
        }
        public function cancelar()
        {
        	$this->regLog();
            $this->_db->rollBack();
        }
        public function regLog()
        {
            $error =$this->_db->errorInfo();
            if(is_writable(LOG_PATH."logdb.txt"))
            {
         
               if($log = fopen(LOG_PATH."logdb.txt","a+"))
               {
                   if(!empty($error['2']))
                   {
                      fwrite($log, date("F j, Y, g:i a").'  '.$error['2']. chr(13));
                   }    
                   fclose($log);
                   return $error['2'];
               }
            }else
            {
                return $error['2'];
            }
            
            
            
        } 
		public function ultimo()
		{
			return $this->_db->lastInsertId();
		}	
}

?>