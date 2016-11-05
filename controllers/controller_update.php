<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Controller_update extends Controller
{
	function __construct()
	{
		$this->model = new Model_update();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('update_view.php', $this->model);
	}
}