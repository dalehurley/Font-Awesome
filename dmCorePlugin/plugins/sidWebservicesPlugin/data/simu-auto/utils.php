<?php

function format_decimal($value) {

	$DECIMAL_SEPARATOR = '.';
	$THOUSANDS_SEPARATOR = ' ';
	$NB_DECIMAL_DIGITS = 2;

	return (number_format($value, $NB_DECIMAL_DIGITS, $DECIMAL_SEPARATOR, $THOUSANDS_SEPARATOR));
}
?>