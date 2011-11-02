<?php // var $sections
echo _open('ul.elements');
foreach($sections as $section){
echo _open('li.element');
echo _link($section)->text($section->getTitle());
echo _close('li');
}
echo _close('ul');
