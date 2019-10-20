<?php
class Controller {
	/* 
	This function takes a view name as parameter and will get the file if it exists.
	A view is a part of a website that is used only once in the website (Ex: The login view)
	*/
	public static function createView($viewName){
		require_once('./views/'.$viewName.'.view.php');
	}
	
	/* 
	This function takes a module name as parameter and will get the file if it exists.
	A module is a part of website that is used more than once and on different part of the website (Ex: The header)
	*/
	public static function createModule($moduleName){
		require_once('./views/'.$moduleName.'.module.php');
	}


}

