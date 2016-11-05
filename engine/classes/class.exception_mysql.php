<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
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
