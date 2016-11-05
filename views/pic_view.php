<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

if($_GET['id_template'] and $_GET['id_user']){
	$result = $data->countUser();
}

$img = ImageCreateTrueColor(1,1);
header ("Content-type: image/gif");
imagegif($img);
imagedestroy($img);

?>