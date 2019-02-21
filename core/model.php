}
    
    <?php
// Autor: Rafael Perez
// comment: class data model that provides functionality for operations with Mysql databases 

class model
{
        private $_registry;	
        protected $_db;
        
        private $_data = array();
        private $_rows = array(); 
        
        private $_table;
        private $_fields;       
        private $_values;
        private $_where;
        private $_opt;
        
        
        private $_rowTable;//total de registros de la tabla
        private $_rowSql; // numero de registro que debolvera la consulta
        private $_numPag; //numero de paginas para el total de registro de la tabla 
        private $_rowPag; //registros por pagina 
        
        
        
        public function __construct()
        {
            $this->_rowTable = 0;
            $this->_rowPag = 15;
            $this->_rowSql = 100;
            $this->_numPag = 10;
            
            
            
            $this->_table = get_class($this); 
            
            $this->_registry = registry::getInstancia();
            
            $this->_db = $this->_registry->_db;	
            
            
            
            
            //mapping of fields
            $this->mapFields();
            $this->countRowTable();
            
        }
        //method what return value, search in array $this->_rows
        public function getValue($index)
        {
            if($index)
            {
                if(in_array($index, $this->_rows))
                {
                    return $this->_rows[$index];
                }
            }
            return false;
            
        }
        
        
        
        public function setValue($index,$value)
        {
            
            
        }
        
        //-------------------------------------------------------------------------------------------------------------------------------------------------------
        //Autor:Rafael Perez
        //Comment: method that maps data structure
        private function mapFields()
        {        
            $sql = "select * from ".$this->_table." limit " . $this->_rowSql;
            $res = $this->_db->query($sql);
            if($res)
            {    
                $res->setFetchMode(PDO::FETCH_ASSOC);       
                //$this->_data = $res->fetchAll();
                $data = $res->fetchAll();
                $this->_fields = array_keys($data);
                
                $i=0;
                foreach ($data as $arr)
                {
                   $val['row'][$i] = $arr;                    
                }
                                       
                return true;
            }else
            {
                $this->regLog();
                return false;
            }
     
                        
        }
        //-------------------------------------------------------------------------------------------------------------------------------------------------------
        //Autor:Rafael Perez
        //Comment: method that count data the table
        private function countRowTable()
        {
            $sql = "select count(*)as rows from ".$this->_table ;
            $res = $this->_db->query($sql);
            if($res)
            {    
                $res->setFetchMode(PDO::FETCH_ASSOC);       
                $data = $res->fetch();
                $this->_rowTable = $data['rows'];
                
            }else
                $this->_rowTable = 0 ;
        }
        
        //------------------------------------------------------------------------------------------------------------------------
        //metho that return the row of data
        //------------------------------------------------------------------------------------------------------------------------
        public function getRow($idRow) 
        {
            if($idRow)
            {
                
                for($i=0;$i < count($this->_data);$i++)
                {
                    if($this->_data['row'][$i]=$idRow)
                        $this->_rows = $this->_data['row'][$i];
                }
                return $this->_rows; 

            }
            return FALSE;
            
        }

        //------------------------------------------------------------------------------------------------------------------------
        //Method that generates query simple to database  
        //------------------------------------------------------------------------------------------------------------------------
        public function sQuery()
        {
            $oper = func_num_args();
            
            switch ($oper)
            {
                case 1:
                    list($param) = func_get_args();
                    $fields = $param['fields'];
                    $cond   = $param['cond'];
                    $sql = "select  " . $fields .
                            "  from " . $this->_table .
                            " where " . $cond;
                    
                    break;
                case 2:
                    list($param,$additional) = func_get_args();
                    $fields = $param['fields'];
                    $cond   = $param['cond'];
                    $sql = "select  " . $fields .
                            "  from " . $this->_table .
                            " where " . $cond .
                            " limit " . $additional;              
                    
                    break;
                default :
                    
                
            }
             
            $res = $this->_db->query($sql);
            if($res)
            {    
                $res->setFetchMode(PDO::FETCH_ASSOC);       
                $data = $res->fetchAll();
                $i=0;
                foreach ($data as $arr)
                {
                   $val['row'][$i] = $arr;                    
                }
                return $val;
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
        public function sInsert(array $values)
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
        //method that update field form table 
        public function sUpdate(array $values,$id)
        {
            if($values)
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
                                    $fields[] = $index ." = ".$v;
                            }
                            $prep = $values;
                            $this->_db->prepare("update ".$this->_table." set " . implode(', ',$fields) . " where id_".$this->_table = $id );
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