<?php //var = $bandeauId - $parent
if(isset($bandeauId))
{
echo dm_get_widget('bandeau','show', array('recordId' => $bandeauId));
}
