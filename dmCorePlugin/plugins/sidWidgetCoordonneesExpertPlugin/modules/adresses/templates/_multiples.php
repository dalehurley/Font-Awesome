<?php
// vars : $adresses, $titreBloc
if (dmConfig::get('site_theme_version') == 'v1'){
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
			$adresse2 = '';
			if($adresse->getAdresse2() != NULL){
							$adresse2 =	'<span class="itemprop streetAddress">'.
											'<span class="type" title="Rue">Rue</span>'.
											'<span class="separator">&nbsp;:&nbsp;</span>'.
											'<span class="value" itemprop="streetAddress">'.$adresse->getAdresse2().'</span>'.
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
							$adresse2.
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
}

// <ul class="thumbnails">
// <li class="span4">
// <div class="thumbnail">


elseif (dmConfig::get('site_theme_version') == 'v2'){
	if (count($adresses)) {
		//titre du contenu
		if($titreBloc) echo _tag('h3',$titreBloc);
		echo _open('div', array('class' => 'thumbnails'));
			//ouverture du listing
		    //echo _open('ul');
			
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
				$emailSpan .= _open('span', array('class' => "itemprop mail"));
					$emailSpan .=_link("mailto:".$adresse->getEmail())->text(_tag('i', array('class' => 'icon-envelope'), '&nbsp;').$adresse->getEmail())->set('.btn value itemprop="email"');
				$emailSpan .=_close('span').'<br />';
				}

				$telSpan = '';
				if ($adresse->getTel()){
					$telSpan .= _open('span', array('class' => "itemprop telephone"));
						$telSpan .= _tag('i', array('class' => 'icon-phone value', 'itemprop' => 'telephone'), '&nbsp;').$adresse->getTel();
					$telSpan .= _close('span').'<br />';
				}

				$faxSpan = '';
				if ($adresse->getFax()){
					$faxSpan .= _open('span', array('class' => "itemprop faxNumber"));
						$faxSpan .= _tag('i', array('class' => "icon-print value", 'itemprop' => "faxNumber"), '&nbsp;').$adresse->getFax();
					$faxSpan .= _close('span').'<br />';
				}
				$adresse2 = '';
				if($adresse->getAdresse2() != NULL){
					$adresse2 .=_open('span', array('class' => "itemprop streetAddress"));
						$adresse2 .=_tag('span', array('class' => "value", 'itemprop' => "streetAddress"),$adresse->getAdresse2());
					$adresse2 .=_close('span').'<br />';
					}

				echo _open('div', array('class' =>"span".$nbSpanByAdress." thumbnail itemscope Organization ".$position."", "itemscope" => "itemscope", "itemtype" => "http://schema.org/Organization"));
					echo _open('address');
						echo _open('div.caption');		
							echo _tag('span', array('class' => "itemprop name itemprop=name"),_tag('h4',$adresse->getTitle()));
							echo _open('div', array('class' => "address itemscope PostalAddress", 'itemprop' => "address", "itemscope" => "itemscope", "itemtype" => "http://schema.org/PostalAddress"));
								echo _open('span', array('class' => "itemprop streetAddress"));
									echo _tag('span', array('class' => "value", 'itemprop' => "streetAddress"),$adresse->getAdresse());
								echo _close('span').'<br />';
								echo $adresse2;
								echo _open('span', array('class' => "itemprop postalCode"));
									echo _tag('span', array('class' => "value", 'itemprop' => "postalCode"),$adresse->getCodePostal());
									echo _tag('span', array('class' => "separator"),'&nbsp');
									echo _tag('span', array('class' => "value", 'itemprop' => "addressLocality"),$adresse->getVille());
								echo _close('span');
							echo _close('div');
							echo $emailSpan;
							echo $telSpan;
							echo $faxSpan;
						echo _close('div');
					echo _close('address');
				echo _close('div');

				    	$i++;
		    }
			
		    //fermeture du listing
		    //echo _close('ul.elements');
		echo _close('div');
	} // sinon on affiche rien
}
