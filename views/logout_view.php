<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

unset($_SESSION['sess_admin']);
    
$redirect = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/';
	
header("Location: ".$redirect."");

exit();