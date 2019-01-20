<?php
// clase modelo utilizada para crear los modelos de datos   
class model
{
        private $_name; 
        private $_registry;	
        protected $_db;

        private $_select;
        private $_where;
        private $_table;
        private $_opt;
        
        public function __construct($name)
        {
            $this->_name = $name; 
            $this->_registry = registry::getInstancia();
            $this->_db = $this->_registry->_db;			
        }
        
        
        public function setSelect($value)
        {
            $this->_select = $value;
        }
        public function setWhere($value)
        {
            $this->_where = $value;
        }
        public function setTable($value)
        {
            $this->_table = $value;
        }
        public function setOpt($value)
        {
            $this->_opt = $value;
        }
        
      //Method that generates query to database  
        public function sQuerySelect()
        {
            $oper = func_num_args();
            switch ($oper)
            {
                case 1:
                        list($field) = func_get_args();
                    break;
                case 2:
                        list($field,$where) = func_get_args();
                    break;
                case 3:
                        list($field,$where,$opt) = func_get_args();
                    break;
                case 4:
                        list($field,$where,$opt,$table) = func_get_args();
                    break;
                default :
                    $field = " * ";
                
            }
            
            if(isset($table) && strlen($table)>3)
                $this->setTable ($table);
            else
                $this->setTable ($this->_name);

            if(isset($where) && strlen($where)>3)
                $this->setWhere ($where);

            if(isset($opt) && strlen($opt)>3)
                $this->setOpt($opt);

            if(isset($field))
                $this->setSelect($field);
            
            if($oper == 1)
            {
                 $sql = "select " . $this->_select .
                            "  from " . $this->_table ;
            }    
           if($oper == 2)         
           {
                 $sql = "select  " . $this->_select .
                            "  from " . $this->_table .
                            " where " . $this->_where;               
           }         
           
           if($oper == 3)
           {
                $sql = "select  " . $this->_select .
                            "  from " . $this->_table .
                            " where " . $this->_where.
                            " " . $this->_opt;   
               
           }    
      
            $res = $this->_db->query($sql);
            if($res)
            {    
                $res->setFetchMode(PDO::FETCH_ASSOC);       
                return $res->fetch();
            }else
            {
                $this->regLog();
                return array();
            }
                 
            
        }
        
        
        
        // Method that starts transaction in MYSQL  
        public function start()
        {
           $this->_db->beginTransaction(); 
        }
        // Method that confirms transaction in MYSQL 
        public function confirm()
        {
            $this->_db->commit();
        }
        // Method that cancels transaction in MYSQL 
        public function cancel()
        {
            $this->regLog();
            $this->_db->rollBack();
        }
        
        // Method that return lastest value generated for insert operations 
        public function latest()
        {
            return $this->_db->lastInsertId();
        }
        
        
        //Method that creates error record in file .logdb
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
        
        	
}

?>