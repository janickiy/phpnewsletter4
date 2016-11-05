<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Controller_ajax_log extends Controller
{
	function __construct()
	{
		$this->model = new Model_ajax_log();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('ajax_log_view.php', $this->model);
	}
}