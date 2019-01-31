<?php
//clase base controller  
abstract class controller
{
        private   $_registry;
        protected $_view;
        protected $_request;
        protected $_acl;
        private $_mensaje;    
        public function __construct()
        {
                $this->_registry = registry::getInstancia();
                $this->_acl =  $this->_registry ->_acl;
                $this->_request = $this->_registry->_request;

                $this->_view = new view($this->_request,$this->_acl);
                $this->_mensaje = '';
        }

        abstract public function index();

        //------------------------------------------
        //metodo que carga un modelo de datos a nivel de controlador 
        //-----------------------------------------
        protected function loadModel($modelo,$modulo=false)
        {
                $_nmodelo = $modelo;
            
                $modelo = $modelo.'Model';
                $ruta_modelo = APP_PATH . 'models' . DS . $modelo . '.php';// creamos la ruta del modelo

                if(!$modulo)
                {
                        $modulo = $this->_request->getModulo();
                }

                if($modulo)
                {
                        if($modulo != 'defaults')
                        {
                                $ruta_modelo = APP_PATH .'modules'. DS . $modulo . DS .'models' . DS . $modelo . '.php';
                        }
                }

                //die($ruta_modelo);
                if(is_readable($ruta_modelo))// verificamos que la ruta existe 
                {

                        require_once $ruta_modelo;// requerimos el archivo
                        //----------------------------------------------------------------------------------------------------------
                        //$modelo = new $modelo;// instanciamos la clase contenida en el archivo
                        //Autor: Rafel Perez
                        // 30/01/2019 
                        //----------------------------------------------------------------------------------------------------
                        //change that allows the name of the model to pass to the class
                          $modelo = new $modelo($_nmodelo);
                          
                        return $modelo;// retornamos el objeto intanciado

                }else 
                        throw new Exception('Modelo no encontrado');
        }

        //---------------------------------------------------------
        // metodo que carga una libreria en el entorno de ejecucion
        //---------------------------------------------------------
        protected function getLibrary($libreria)
        {
                $ruta_libreria = APP_PATH . 'libs' . DS . $libreria . '.php';

                if(is_readable($ruta_libreria))
                {
                        require_once $ruta_libreria;

                }else
                        throw new Exception('Libreria no encontrada');

        }

        protected function getTexto($valor)//metodo que filtra un valor tipo texto no recomendado para  inserciones con el metodo prepare de pdo
        {
                if(isset($_POST[$valor]) && !empty($_POST[$valor]))
                {
                        $_POST[$valor] = htmlspecialchars($_POST[$valor],ENT_QUOTES);
                        return $_POST[$valor];
                }else
                        return '';

        }

        protected function getInt($valor)//metodo que filtra  un valor entero 
        {
                if(isset($_POST[$valor]) && !empty($_POST[$valor]))
                {
                        $_POST[$valor] = filter_input(INPUT_POST,$valor,FILTER_VALIDATE_INT);
                        return $_POST[$valor];
                }else
                        return 0;
        }

        protected function redireccionar($ruta=false)// metodo que redirecciona la vista
        {
                if($ruta)
                {
                    if(!empty($this->_mensaje))
                        header("location:".BASE_URL.$ruta.'/'.$this->_mensaje);
                    else
                        header("location:".BASE_URL.$ruta);
                    
                    exit;
                }else
                        {
                            if(!empty($this->_mensaje))
                                header("location:".BASE_URL.$ruta.'/'.$this->_mensaje);
                            else
                                header("location:".BASE_URL.$ruta);
                            exit;
                        }
        }
        protected function getMensaje($tipo,$cadena)
        {
            $this->_mensaje = base64_encode($tipo.':'.$cadena);            
        }
        
        protected function filtrarInt($int)// filtra los datos enteros y si no lo son intenta  convertirlos 
        {
                $int = (int) $int;
                if(is_int($int))
                {
                        return $int;
                }else
                        return 0;
        }

        protected function getPostParam($valor)// retorna los valores de la super global $_POST recomendado para inserciones con el metodo prepare de pdo
        {
                if(isset($_POST[$valor]))
                {
                        return $_POST[$valor];
                }

        }
        protected function getGetParam($valor)// retorna los valores de la super global $_POST recomendado para inserciones con el metodo prepare de pdo
        {
                if(isset($_GET[$valor]))
                {
                        return $_GET[$valor];
                }

        }
        /// limpia las sentencia sql de caracteres de escape
        protected function getSql($clave)
        {
                if(isset($_POST[$clave]) &&  !empty($_POST[$clave]))
                {
                        $_POST[$clave]= strip_tags($_POST[$clave]);
                        if(!get_magic_quotes_gpc())
                        {
                                $_POST[$clave]=$this->mres($_POST[$clave]);
                        }
                        return trim($_POST[$clave]);

                }

        }
        // esta funcion convierte el valor enviado en caracretes aceptando solo valor de la a a la z y del 0 al 9 
        protected function getAlphaNum($clave)
        {
                if(isset($_POST[$clave]) && !empty($_POST[$clave]))
                {
                        $_POST[$clave]=(string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
                        return trim($_POST[$clave]);
                }

        }
        // funccion que valida las direcciones de correo
        public function validarEmail($email)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                return true;
            }

            return false;
        }
        protected function mres($value)
        {
                $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
                $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
        }

        public function generaPass()
        {
                $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
                $longitudCadena=strlen($cadena);
                $pass = "";
                $longitudPass=10;
                for($i=1 ; $i<=$longitudPass ; $i++){
                        $pos=rand(0,$longitudCadena-1);
                        $pass .= substr($cadena,$pos,1);
                }
                return $pass;
        }


} 


?>