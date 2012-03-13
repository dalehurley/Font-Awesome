<?php
// vars : $adresses, $titreBloc

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
		if ($adresse->getFax()){
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

