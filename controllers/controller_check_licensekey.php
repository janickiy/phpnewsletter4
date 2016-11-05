<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Controller_check_licensekey extends Controller
{
	function __construct()
	{
		$this->model = new Model_check_licensekey();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('check_licensekey_view.php', $this->model);
	}
}