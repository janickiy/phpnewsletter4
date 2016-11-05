<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Controller_create_template extends Controller
{	
	function __construct()
	{
		$this->model = new Model_create_template();
		$this->view = new View();
	}

	public function action_index()
	{
		$this->view->generate('create_template_view.php', $this->model);
	}
}

?>
