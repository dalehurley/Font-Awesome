<?php // Vars: $accueil
// Vars: $nom
echo _open('div.entete');
echo _tag('h1', $nom->getTitle());
echo _tag('h2', __('Accounting and statutory audit'));
echo _tag('p.region', __('Accountants registered with the College of Chartered Accountants Region')." ".$nom->getRegionEc());
echo _tag('p.region', __('Auditors registered with the Regional Company of')." ".$nom->getRegionCc());
echo _close('div'); // fin div.entete