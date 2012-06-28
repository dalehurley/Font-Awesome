<?php

$infos = json_decode($sid_contact_data->infos, true);
foreach ($infos as $key => $value) {
	echo '<b>'.$key.'</b> : '.$value.'<br/>';
}


