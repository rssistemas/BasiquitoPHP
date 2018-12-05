<?php

class session
{
        /*
         *metodo que inicia una session  
         */
        public static function init()
        {
                session_start();
        }
        /*
         * metodo de destruye la session, una variable de session o varias variables de session  
         */
        public static function destroy($clave=false)
        {
                if($clave)
                {
                        if(is_array($clave))
                        {
                                for($i=0;$i<count($clave);$i++)
                                {
									if(isset($_SESSION[$clave[$i]]))
									{
										unset($_SESSION[$clave[$i]]);
									}
                                }
                        }else
							{
								if(isset($_SESSION[$clave]))
								{
									unset($_SESSION[$clave]);
								}
							}
                }else
                        {
                                session_destroy();
                        }			
        }
        //-----------------------------------------------
        // metodo que carga variables en session
        //-----------------------------------------------
        public static function set($clave,$valor)
        {
                if(!empty($clave))
                        $_SESSION[$clave]=$valor; 
        }

        //-----------------------------------------------------
        // metodo que muestra una variable guardada en sission
        //-----------------------------------------------------
        public static function get($clave)
        {
                if(isset($_SESSION[$clave]))
                        return $_SESSION[$clave];
        }
        //-----------------------------------------------------
        //metodo que verifica si una cariable existe en session 
        //-----------------------------------------------------
        public static function has($clave)
        {
                if(isset($_SESSION[$clave]))
                        return true;
                else
                    return FALSE;
        }    
        //-----------------------------------------
        //controla el acceso a nivel de usuario
        //------------------------------------------
        public static function acceso($level = FALSE)
        {
                if(!session::get('autenticado'))
                {
                        header("location:".BASE_URL.'entrada/index/login/');
                        exit();
                }

                session::tiempo();

                //if(session::getLevel($level) > session::getLevel(session::get('level')))
                //{
                //	header("location:".BASE_URL.'error/access/5050');
                //	exit();
                //}
        }	

        //-----------------------------------------------------------
        // metodo que maneja los accesos a nivel de vista
        //-----------------------------------------------------------
        public static function accesoView($level)
        {
                if(!session::get('autenticado'))
                {
                        return false;
                }

                $this->tiempo();

                if(session::getLevel($level) > session::getLevel(session::get('level')))
                {
                        return FALSE;
                }

                return TRUE;
        }

        //------------------------------------------------------------
        //metodo que retorna el nivel de acceso
        //------------------------------------------------------------
        public static function getLevel($level)
        {
                $role = array();
                $role['admin']=1;
                $role['especial']=2;
                $role['usuario']=3;

                if(!array_key_exists($level, $role))
                {
                        throw new Exception("Error de Acceso...");
                }else
                        return $role[$level];



        }

        //----------------------------------------------------------------
        // metodo que maneja el acceso a nivel de grupo de usuario
        //----------------------------------------------------------------
        public static function accesoEstricto(array $level, $noAdmin=FALSE)
        {
                if(!session::get('autenticado'))
                {
                        header("location:".BASE_URL.'error/access/5050');
                        exit();
                }
                $this->tiempo();
                if($noAdmin==false)
                {
                        if(session::get('level')=='admin')
                                return; 
                }

                if(count($level))
                {
                        if(in_array(session::get('level'), $level))
                        {
                                return;
                        }
                }

                header("location:".BASE_URL.'error/access/5050');
                exit();

        }

        public static function accesoViewEstricto(array $level, $noAdmin=FALSE)
        {
                if(!session::get('autenticado'))
                {
                        return FALSE;
                }

                $this->tiempo();

                if($noAdmin==false)
                {
                        if(session::get('level')=='admin')
                                return TRUE; 
                }

                if(count($level))
                {
                        if(in_array(session::get('level'), $level))
                        {
                                return TRUE;
                        }
                }

                return FALSE;

        }

        public static function tiempo()
        {
                if(!session::get('tiempo') || !defined('SESSION_TIME'))
                {
                        throw new Exception("No se ha definido el tiempo de la session ....");
                }
                if(SESSION_TIME == 0)
                        return;

                if( (time()- session::get('tiempo')) > (SESSION_TIME * 60))
                {
                        //session::destroy();
                        header("location:".BASE_URL.'entrada/index/logout/');
                        exit();				
                }else
                        {
                            session::set('tiempo', time());
                        }
        }

}

?>
