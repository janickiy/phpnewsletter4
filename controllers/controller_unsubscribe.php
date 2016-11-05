<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Controller_unsubscribe extends Controller
{
	function __construct()
	{
		$this->model = new Model_unsubscribe();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('unsubscribe_view.php',$this->model);
	}
}

?>