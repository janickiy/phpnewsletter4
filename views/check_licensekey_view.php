<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

$result = Auth::checkLicenseKey();

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<document>\n";
	
if(!$result) {
	if(Auth::getLicenseKeyType() == 'demo') 
		$error_msg = $PNSL["lang"]["error"]["trial_license_has_expired"];
	else
		$error_msg = $PNSL["lang"]["error"]["invalid_license"];

	echo "<error_msg>".htmlspecialchars(str_replace('%BUY_LICENSE_LINK%', 'http://janicky.com/php-scripts/pochtovaya-rassylka', $error_msg))."</error_msg>\n";
	echo "<result>no</result>";
}
else{
	//echo "<error_msg></error_msg>\n";
	echo "<result>yes</result>";
}
	
echo "</document>";	

?>