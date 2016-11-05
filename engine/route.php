<?php

class Route
{
	static function start()
	{
		try
		{
			$controller_name = 'template';
			$action_name = 'index';
		
			$_GET['action'] = trim($_GET['action']);
			$_GET['task'] = trim($_GET['task']);
		
			if(!empty($_GET['task'])){	
				$controller_name = $_GET['task'];
			}		
		
			if(!empty($_GET['action']))	{
				$action_name = $_GET['action'];
			}		

			$model_name = 'Model_'.$controller_name;
			$controller_name = 'Controller_'.$controller_name;
			$action_name = 'action_'.$action_name;

			$model_file = strtolower($model_name).'.php';
			$model_path = "models/".$model_file;
			
			if(file_exists($model_path)){
				require_once "models/".$model_file;
			}
			else{
				Route::ShowError('404 Page Not Found!');
			}

			$controller_file = strtolower($controller_name).'.php';
			$controller_path = "controllers/".$controller_file;
			
			if(file_exists($controller_path)){
				require_once "controllers/".$controller_file;
			}
			else{
				Route::ShowError('404 Page Not Found!');
			}			
			
			if(class_exists($controller_name)){
				$controller = new $controller_name;
				$action = $action_name;
		
				if(method_exists($controller, $action))	{
					$controller->$action();
				}
				else{
					Route::ShowError('404 Page Not Found!');
				}
			}
			else{
				Route::ShowError('404 Page Not Found!');
			}
		}
		catch(ExceptionMySQL $exc)
		{
			if(DEBUG == 1){
				echo "<!DOCTYPE html>";
				echo "<html>";
				echo "<head>";
				echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
				echo "<title>Error</title>";
				echo "</head>";
				echo "<body>";
				echo "<p>An error occurred while accessing the MySQL database!</p>";
				echo "<p>".$exc->getMySQLError()."<br>".nl2br($exc->getSQLQuery())."</p>";
				echo "<p>Error in file ".$exc->getFile()." at line ".$exc->getLine()."</p>";
				echo "</body>";
				echo "</html>";
			}
			else{
				$redirect = "http://".$_SERVER['SERVER_NAME'].root()."error.php";
				header('HTTP/1.1 500 Internal Server Error');
				header("Location: ".$redirect."");
				exit();
			}
		}
		catch(Exception $exc)
		{
			echo "<!DOCTYPE html>";
			echo "<html>";
			echo "<head>";
			echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
			echo "<title>Error</title>";
			echo "</head>";
			echo "<body>";
			echo "<p>".$exc->getMessage()."</p>";
			echo "</body>";
			echo "</html>";			
		}		
	}
	
	function ShowMySQLError($error,$query,$msg)
	{
		throw new ExceptionMySQL($error,$query,$msg);	
	}
	
	function ShowError($msg)
	{
		throw new Exception($msg);	
	}	
}

?>
