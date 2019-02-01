<?php
// Autor: Rafae Perez
// comment: class data model that provides functionality for operations with Mysql databases 

class model
{
        private $_registry;	
        protected $_db;
        private $_table;
        private $_fields;       
        
        private $_where;
        private $_opt;
        
        public function __construct($name )
        {
            $this->setTable( $name); 
            $this->_registry = registry::getInstancia();
            $this->_db = $this->_registry->_db;	
            
            //mapping of fields
            $this->mapFields();
            
        }
        //-------------------------------------------------------------------------------------------------------------------------------------------------------
        //Autor:Rafael Perez
        //Comment: method that maps data structure
        private function mapFields()
        {        
                $sql = "select * from ".$this->_table." limit 1";
                $res = $this->_db->query($sql);
                if($res)
                {    
                    $res->setFetchMode(PDO::FETCH_ASSOC);       
                   $data = $res->fetch();
                   $fields = array_keys($data);                   
                    $this->setFields( $fields);
                    return true;
                }else
                {
                    $this->regLog();
                    return false;
                }
     
                        
        }
        
        public function setFields($value)
        {
            $this->_fields = $value;
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
                 $sql = "select " . $this->_fields .
                            "  from " . $this->_table ;
            }    
           if($oper == 2)         
           {
                 $sql = "select  " . $this->_fields .
                            "  from " . $this->_table .
                            " where " . $this->_where;               
           }         
           
           if($oper == 3)
           {
                $sql = "select  " . $this->_fields .
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
           
        
         //-------------------------------------------------------------------------------------------------------------------------------------------------------
        //Autor:Rafael Perez
        //Comment: method that generates insertion query
        //parameters: array(":index"->value,........) or array("index"->value)
        public function sQueryInsert(array $values)
        {
                if(count($this->_fields)>0)
                {
                    if(count($values) != count($this->_fields) ) 
                    {
                            $fields = array();
                            foreach($values as $k => $v )
                            {
                               if(strpos(":", $v)) 
                                    $index = str_replace(":","",$k);
                               else
                                   $index = $k;
                                    $fields[] = $index;
                            }
                            $prep = $values;
                            $this->_db->prepare("INSERT INTO ".$this->_table."( " . implode(', ',$fields) . ") VALUES (" . implode(', ',array_keys($prep)) . ")");
                    }else
                    {
                            $prep = array();
                            foreach($values as $k => $v )
                            {
                                $prep[':'.$k] = $v;
                            }
                            $this->_db->prepare("INSERT INTO ".$this->_table."( " . implode(', ',array_keys($this->_fields)) . ") VALUES (" . implode(', ',array_keys($prep)) . ")");
                    }                   
                    
                    $res = $sth->execute($prep);
                    if(!$res)
                    {
                        $this->regLog();
                        return FALSE;
                    } else {
                            return true;
                    }
                    
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