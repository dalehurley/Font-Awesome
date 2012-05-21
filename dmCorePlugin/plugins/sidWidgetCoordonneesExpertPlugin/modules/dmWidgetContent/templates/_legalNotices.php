<?php

// vars : $titreBloc, $text, $defaultInfos, $officeInfos
//titre du contenu
if($titreBloc == NULL) {$titreBloc = __('Legal notices');};
echo '<h2 class="title">' . $titreBloc . '</h2>';
echo _open('div.wrapper');
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

//ouverture du listing
//    echo _open('ul.elements');
//	
//	//compteur
//	$i = 1;
//	$i_max = count($adresses);
//	
//    foreach($adresses as $adresse) {
//
//		$position = '';
//        switch ($i){
//            case '1' : 
//            	if ($i_max == 1) $position = ' first last';
//            	else $position = ' first';
//                break;
//            default : 
//            	if ($i == $i_max) $position = ' last';
//            	else $position = '';
//            	break;
//        }
//
//		$emailSpan = '';
//		if ($adresse->getEmail()){
//		$emailSpan = '<span class="itemprop email">'.
//						'<span class="type" title="Email">Email</span>'.
//						'<span class="separator">&nbsp;:&nbsp;</span>'.
//						'<span class="value"><a class="link" href="mailto:'.$adresse->getEmail().'" itemprop="email">'.$adresse->getEmail().'</a></span>'.
//					'</span>';
//		}
//
//		$telSpan = '';
//		if ($adresse->getTel()){
//			$telSpan = '<span class="itemprop telephone">'.
//						'<span class="type" title="Téléphone">Téléphone</span>'.
//						'<span class="separator">&nbsp;:&nbsp;</span>'.
//						'<span class="value" itemprop="telephone">'.$adresse->getTel().'</span>'.
//					'</span>';
//		}
//
//		$faxSpan = '';
//		if ($adresse->getFax()){
//			$faxSpan = '<span class="itemprop faxNumber">'.
//						'<span class="type" title="Fax">Fax</span>'.
//						'<span class="separator">&nbsp;:&nbsp;</span>'.
//						'<span class="value" itemprop="faxNumber">'.$adresse->getFax().'</span>'.
//					'</span>';
//		}
//
//
//		echo '	<li class="element itemscope Organization'.$position.'" itemscope="itemscope" itemtype="http://schema.org/Organization">'.
//					'<span class="itemprop name" itemprop="name">'.
//						$adresse->getTitle().
//					'</span>'.
//					'<div itemprop="address" class="address itemscope PostalAddress" itemscope="itemscope" itemtype="http://schema.org/PostalAddress">'.
//						'<span class="itemprop streetAddress">'.
//							'<span class="type" title="Rue">Rue</span>'.
//							'<span class="separator">&nbsp;:&nbsp;</span>'.
//							'<span class="value" itemprop="streetAddress">'.$adresse->getAdresse().'</span>'.
//						'</span>'.
//						'<span class="subWrapper">'.
//							'<span class="itemprop postalCode">'.
//								'<span class="type" title="Postal Code">Postal Code</span>'.
//								'<span class="separator">&nbsp;:&nbsp;</span>'.
//								'<span class="value" itemprop="postalCode">'.$adresse->getCodePostal().'</span>'.
//							'</span> '.
//							'<span class="itemprop addressLocality">'.
//								'<span class="type" title="Localité">Localité</span>'.
//								'<span class="separator">&nbsp;:&nbsp;</span>'.
//								'<span class="value" itemprop="addressLocality">'.$adresse->getVille().'</span>'.
//							'</span>'.
//						'</span>'.
//					'</div>'.
//					$emailSpan.
//					$telSpan.
//					$faxSpan.
//				'</li>';
//
//		    	$i++;
//    }
//	
//    //fermeture du listing
//    echo _close('ul.elements');

echo markdown($text);
