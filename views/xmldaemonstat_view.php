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

session_write_close();

$status = $data->getMailingStatus();

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<document>\n";
echo "<status>".$status."</status>\n";
echo "</document>";