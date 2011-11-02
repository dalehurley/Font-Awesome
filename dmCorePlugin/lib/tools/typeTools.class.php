<?php

/**
 * class typeTools
 *
 */

class typeTools
{
	public static function validMail($str){

		$str = ereg_replace("<[^>]*>", "", trim(strip_tags($str)) ) ;
	$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
   if(preg_match($Syntaxe,$adresse))
      return true;
   else
     return false; 
		return $str;
	}

}

