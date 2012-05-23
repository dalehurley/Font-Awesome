<?php

// vars : $titreBloc, $text, $defaultInfos, $officeInfos
//titre du contenu
if($titreBloc == NULL) {$titreBloc = __('Legal notices');};
echo '<h2 class="title">' . $titreBloc . '</h2>';

echo _open('div.wrapper');
if($defaultInfos == TRUE){
    // composition du bloc perso au cabinet
    echo _tag('h3', __('The office'));
    if ($defaultInfos == TRUE) {

        echo _open('div', array('class' => "address", 'itemprop' =>'address', 'itemscope' =>'', 'itemtype' =>'http://schema.org/PostalAddress'));
            echo _tag('strong', $officeInfos->getTitle());
            echo _tag('br');
            // composition de l'adresse postal
            echo _open('span.streetAddress itemprop="streetAddress"');
                echo $officeInfos->getAdresse();
                if ($officeInfos->getAdresse2() != NULL) {
                    echo '&nbsp;-&nbsp;' . $officeInfos->getAdresse2();
                }
            echo _close('span');
            echo '&nbsp;-&nbsp;';
            echo _tag('span.postalCode itemprop="postalCode"', $officeInfos->getCodePostal());
            echo '&nbsp;';
            echo _tag('span.addressLocality itemprop="addressLocality"', $officeInfos->getVille());
            echo _tag('br');
            // composition du tél et fax
            echo _open('span.telephone');
            echo _tag('span.type', __('phone'));
            echo '&nbsp;';
            echo _tag('span.value itemprop="telephone"', $officeInfos->getTel());
            echo _close('span');


            if ($officeInfos->getFax() != NULL) {
                echo '&nbsp;-&nbsp;';
                echo _open('span.faxNumber');
                echo _tag('span.type', __('fax'));
                echo '&nbsp;';
                echo _tag('span.value itemprop="faxNumber"', $officeInfos->getFax());
                echo _close('span');
            }
            echo _tag('br');

            // composition du RCS
            if ($officeInfos->getRcs() != NULL) {
                if ($officeInfos->getRcsLocality()) {
                    $rcsLocality = $officeInfos->getRcsLocality();
                } else
                    $rcsLocality = '';
                echo _tag('span', 'RCS '.$officeInfos->getRcs() . ' ' . $rcsLocality);
                echo _tag('br');
            }
        
            // composition du n° ORIAS
            if($officeInfos->getOrias() != NULL){
                echo _tag('span',__('The Register of insurance intermediaries under the number ORIAS').' '.$officeInfos->getOrias().'.');
                echo _tag('br');
            }

            // composition du n° ANACOFI-CIF
            if($officeInfos->getCif() != NULL){
                echo _tag('span',__('Member of the National Association of Financial Advisors ANACOFI-CIF, approved by the AMF, and registered as an investment consultant, ICF, under number').' '.$officeInfos->getCif().'.');
                echo _tag('br');
            }

            // composition de la carte professionnelle
            if($officeInfos->getCartePro() != NULL){
                echo _tag('span',$officeInfos->getTitle().' '.__('is registered with the Prefecture of Police of the City:').' '.$officeInfos->getCarteProLocality().', '.__('for the activities of real estate transactions, business card holder n°').$officeInfos->getCartePro().'.');
                echo _tag('br');


            // composition texte CNIL
                echo _tag('span',__('Under Law 78-17 of 6 January 1978, on "information technology, files and liberties"').', '.$officeInfos->getTitle().' '.__('has declared to the CNIL detention of information collected on').' '.dmConfig::get('site_ndd'));
                echo _tag('br');
                echo _tag('span',__('Users of this site have the right to access, rectify and delete data concerning them by contacting directly').' '.$officeInfos->getTitle());
            }
        echo _close('div');
    }
    
        echo _close('div');
        echo _tag('br');


echo _open('div.wrapper');
echo _tag('h3',__('Edition'));
echo _tag('strong','SAS SID Presse');
echo _tag('br');  
echo _tag('strong',__('Headquarters'));
echo _tag('p','16, rue du 4 Septembre - 75002 Paris<br />Capital social : 1 728 750 €<br />RCS 381 123 868 Paris');  
echo _tag('strong',__('Production and administrative'));
echo _tag('br');
echo _tag('p','15, rue de la Demi-Lune - BP 1119 - 86061 Poitiers Cedex 9<br />Tél. 05 49 60 20 60  -  Fax 05 49 01 87 08');
echo _tag('strong',__('Director of Publication').' : ');
echo _tag('span','Francis Morel');
echo _tag('br');
echo _tag('strong',__('Editor').' : ');
echo _tag('span','Laurent David');
echo _tag('br');
echo _tag('h3',__('Accommodation'));
echo _tag('strong','Novius');
echo _tag('br');
echo _tag('strong',__('Headquarters'));
echo _tag('br');
echo _tag('span','55, avenue Galline - 69100 Villeurbanne<br />RCS 443 207 154 Villeurbanne');
echo _close('div');
}
else {
echo markdown($text);
}