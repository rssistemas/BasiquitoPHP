<?php
        //-----------------------------------------------------------------------
	//framework basiquitoPHP
	//clase bootstrap; procesa las llamadas a los controladores y a los metodos  
	//------------------------------------------------------------------------
	class bootstrap{
		
		public static function start( request $peticion)
		{
			$modulo = $peticion->getModulo();
					
			$controller = $peticion->getControlador(). 'Controller';
			
			$rutaControlador = APP_PATH . 'controllers'. DS . $controller . '.php';
			
			$metodo = $peticion->getMetodo();
			
			$argumento=$peticion->getArgumento();
			
			
		
			if($modulo)
			{
				$rutaModulo = APP_PATH . 'controllers' . DS . $modulo . 'Controller.php'; 
			
				if(is_readable($rutaModulo))
				{
					require_once $rutaModulo;
					$rutaControlador = APP_PATH . 'modules' . DS . $modulo . DS . 'controllers' . DS . $controller.'.php'; 
				
				}else
					{
						throw new Exception("Error de base de Modulo");
					}
				
			}else
				{
					$rutaControlador = APP_PATH . 'controllers'. DS . $controller . '.php';
				}
			
			//die($rutaControlador);
			
			// si el archivo existe y es leible
			if(is_readable($rutaControlador))
			{
				// importamos el archivo								
				require_once $rutaControlador;
				
				// instanciamos la clase
				$controller = new $controller; 
				// si el metodo del controlador no es ejecutable o no existe establece el metodo en index
				if(!is_callable(array($controller,$metodo)))
				{
					$metodo = 'index';
				}
				// si los argumentos existen
				if(isset($argumento))
				{
					// se invocan los metodos contenidon en una clase pasandole los argumentos
					call_user_func_array(array($controller,$metodo),$argumento);
				}else
					{
						// se invoca el metodo contenido en una clase sin argumentos
						call_user_func(array($controller,$metodo));
					}
			
				unset($_GET['url']);	
			  
			}else
			{
                            throw new Exception('Controlador No encontrado'. $rutaControlador);
			}
				
		}
		
	}

