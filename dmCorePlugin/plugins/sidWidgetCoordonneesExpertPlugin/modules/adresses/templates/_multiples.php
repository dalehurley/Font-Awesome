<?php

//insertions des includes nécessaires à ce partial
$initValues = sfConfig::get('dm_front_dir') . '/templates/_schema/_partialInitValues.php';
include $initValues;

// vars : $adresses, $titreBloc
$html = '';

if (count($adresses)) {
	//titre du contenu
	if($titreBloc) echo '<h4 class="title">'.$titreBloc.'</h4>';
	
	//ouverture du listing
    echo _open('ul.elements');
	
	//compteur
	$i = 1;
	$i_max = count($adresses);
	
    foreach($adresses as $adresse) {

		$position = '';
        switch ($i){
            case '1' : 
            	if ($i_max == 1) $position = ' first last';
            	else $position = ' first';
                break;
            default : 
            	if ($i == $i_max) $position = ' last';
            	else $position = '';
            	break;
        }


/*		
		//affichage du contenu
		$addressOpts = array(
							'name' => $adresse->getTitle(),
							'addressLocality' => $adresse->getVille(),
							'postalCode' => $adresse->getCodePostal(),
							'email' => $adresse->getEmail(),
							'faxNumber' => $adresse->getFax(),
							'telephone' => $adresse->getTel(),
							'container' => 'li.element',
							'count' => $count,
							'maxCount' => $maxCount
						);
		$addressOpts['streetAddress'] = $adresse->getAdresse();
		if ($adresse->getAdresse2() != NULL) $addressOpts['streetAddress'].= $dash . $adresse->getAdresse2();
		
		$html.= get_partial('global/schema/Thing/Organization', $addressOpts);
*/
		$emailSpan = '';
		if ($adresse->getEmail()){
		$emailSpan = '<span class="itemprop email">'.
						'<span class="type" title="Email">Email</span>'.
						'<span class="separator">&nbsp;:&nbsp;</span>'.
						'<span class="value"><a class="link" href="mailto:'.$adresse->getEmail().'" itemprop="email">'.$adresse->getEmail().'</a></span>'.
					'</span>';
		}

		$telSpan = '';
		if ($adresse->getTel()){
			$telSpan = '<span class="itemprop telephone">'.
						'<span class="type" title="Téléphone">Téléphone</span>'.
						'<span class="separator">&nbsp;:&nbsp;</span>'.
						'<span class="value" itemprop="telephone">'.$adresse->getTel().'</span>'.
					'</span>';
		}

		$faxSpan = '';
		if ($adresse->getTel()){
			$faxSpan = '<span class="itemprop faxNumber">'.
						'<span class="type" title="Fax">Fax</span>'.
						'<span class="separator">&nbsp;:&nbsp;</span>'.
						'<span class="value" itemprop="faxNumber">'.$adresse->getFax().'</span>'.
					'</span>';
		}


		echo '	<li class="element itemscope Organization'.$position.'" itemscope="itemscope" itemtype="http://schema.org/Organization">'.
					'<span class="itemprop name" itemprop="name">'.
						$adresse->getTitle().
					'</span>'.
					'<div itemprop="address" class="address itemscope PostalAddress" itemscope="itemscope" itemtype="http://schema.org/PostalAddress">'.
						'<span class="itemprop streetAddress">'.
							'<span class="type" title="Rue">Rue</span>'.
							'<span class="separator">&nbsp;:&nbsp;</span>'.
							'<span class="value" itemprop="streetAddress">'.$adresse->getAdresse().'</span>'.
						'</span>'.
						'<span class="subWrapper">'.
							'<span class="itemprop postalCode">'.
								'<span class="type" title="Postal Code">Postal Code</span>'.
								'<span class="separator">&nbsp;:&nbsp;</span>'.
								'<span class="value" itemprop="postalCode">'.$adresse->getCodePostal().'</span>'.
							'</span> '.
							'<span class="itemprop addressLocality">'.
								'<span class="type" title="Localité">Localité</span>'.
								'<span class="separator">&nbsp;:&nbsp;</span>'.
								'<span class="value" itemprop="addressLocality">'.$adresse->getVille().'</span>'.
							'</span>'.
						'</span>'.
					'</div>'.
					$emailSpan.
					$telSpan.
					$faxSpan.
				'</li>';

		    	$i++;
    }
	
    //fermeture du listing
    echo _close('ul.elements');
} // sinon on affiche rien

