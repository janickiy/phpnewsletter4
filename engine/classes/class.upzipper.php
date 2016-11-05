<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Unzipper {
	
	public $localdir = '.';
	public $zipfile;
	public static $status = '';
	public static $result = '';
	
	public function __construct($zipfile)
	{
		$this->zipfile = $zipfile;
		
		if($this->zipfile != '') {
			self::extract($this->zipfile, $this->localdir);
		}
	}
	
	public static function extract($archive, $destination)
	{
		global $PNSL;
	
		$ext = pathinfo($archive, PATHINFO_EXTENSION);
		
		if($ext == 'zip') {
			self::extractZipArchive($archive, $destination);
		}
		else{
			if($ext == 'gz') {
				self::extractGzipFile($archive, $destination);
			}
		}
	}
	
	public static function extractZipArchive($archive, $destination)
	{
		global $PNSL;
	
		if(!class_exists('ZipArchive')) {
			self::$status = $PNSL["lang"]["msg"]["php_doesnt_support_unzip_func"];
			self::$result = 'no';
			return;
		}
		
		$zip = new ZipArchive;
		
		if($zip->open($archive) === TRUE) {
			if(is_writeable($destination . '/')) {
				$zip->extractTo($destination);
				$zip->close();
				self::$status = $PNSL["lang"]["msg"]["files_unzipped_successfully"];
				self::$result = 'yes';
			}
			else{
				self::$status = $PNSL["lang"]["msg"]["directory_not_writeable"];
				self::$result = 'no';
			}
		}
		else {
			self::$status = $PNSL["lang"]["msg"]["cannot_read_zip_archive"];
			self::$result = 'no';
		}
	}
	  
	public static function extractGzipFile($archive, $destination)
	{
		global $PNSL;
	
		if(!function_exists('gzopen')) {
			self::$status = $PNSL["lang"]["msg"]["has_no_zlib_support_enabled"];
			self::$result = 'no';
			return;
		}
		
		$filename = pathinfo($archive, PATHINFO_FILENAME);
		$gzipped = gzopen($archive, "rb");
		$file = fopen($filename, "w");
		
		while($string = gzread($gzipped, 4096)) {
			fwrite($file, $string, strlen($string));
		}
		
		gzclose($gzipped);
		fclose($file);
		
		if(file_exists($destination . '/' . $filename)) {
			self::$status = $PNSL["lang"]["msg"]["files_unzipped_successfully"];
			self::$result = 'yes';
		}
		else{
			self::$status = $PNSL["lang"]["msg"]["error_unzipping_file"];
			self::$result = 'no';
		}
	}
}

?>