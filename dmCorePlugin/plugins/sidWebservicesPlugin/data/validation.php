<?php
function isInteger($value)
{
  if(!preg_match("/^[0-9]+$/ ",$value))
    return FALSE;
  else
    return TRUE;
}

function isDecimal($value)
{
  if(!preg_match("/([0-9]*[\.]?[0-9]+)/",$value))
    return FALSE;
  else
    return TRUE;
}

function checkInteger($name, $value, $sampleValue) {
	if(!isInteger($value))
		return invalidMessage($name, "un nombre entier", $sampleValue);
	else
		return "";
}

function checkDecimal($name, $value, $sampleValue) {
	if(!isDecimal($value))
		return invalidMessage($name, "un nombre d&eacute;cimal", $sampleValue);
	else
		return "";
}

function checkSup($name1, $value1, $name2, $value2) {
	
	if($value1<$value2)
	
		return "Le $name1 doit être supérieur ou égal aux $name2.<BR />";
	else 
		return "";
}

function calculLoyersMV($montant,$valeur){
	if($valeur>$montant)
		return ("Valeur de rachat erron&eacute;e<br>");
	else
		return "";
}
		
function calculLoyersMD($montant,$depot){		
	if($depot>$montant)
		return ("D&eacute;p&ocirc;t initial sup&eacute;rieur au montant financ&eacute;");
	else
		return "";
}

function controljours($nbjours1,$nbjours2){		
	if($nbjours1 > 30 || $nbjours2 > 25)
		return ("Le nombre de jours est trop grand");
	else
		return "";
}

function invalidMessage($name, $typeName, $sampleValue) {
	return "Le champ $name doit &eacute;tre $typeName <i>(ex. : $sampleValue)</i>.<BR />";
}

?>