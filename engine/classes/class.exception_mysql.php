<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class ExceptionMySQL extends Exception
{
	protected $mysql_error;
	protected $sql_query;

	public function __construct($mysql_error, $sql_query, $message)
	{
		$this->mysql_error = $mysql_error;
		$this->sql_query = $sql_query;

		parent::__construct($message);
	}

	public function getMySQLError()
	{
		return $this->mysql_error;
	}

	public function getSQLQuery()
	{
		return $this->sql_query;
	}
}

?>
