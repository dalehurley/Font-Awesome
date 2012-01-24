<?php
echo '<div class="organisation" itemscope itemtype="http://schema.org/Organization">';
	echo _tag('span.name itemprop="name"', $renseignements->getTitle());
	echo '<div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
		echo _open('span.streetAddress itemprop="streetAddress"');
			echo $renseignements->getAdresse();
			if ($renseignements->getAdresse2() != NULL)
			{
				echo '&nbsp;-&nbsp;' . $renseignements->getAdresse2();
			}
		echo _close('span');
		echo '&nbsp;-&nbsp;';
		echo _tag('span.postalCode itemprop="postalCode"', $renseignements->getCodePostal());
		echo '&nbsp;';
		echo _tag('span.addressLocality itemprop="addressLocality"', $renseignements->getVille());
	echo _close('div');

	echo _open('span.telephone');
		echo _tag('span.type', __('phone'));
		echo '&nbsp;';
		echo _tag('span.value itemprop="telephone"', $renseignements->getTel());
	echo _close('span');

	if ($renseignements->getFax() != NULL) {
		echo '&nbsp;-&nbsp;';
		echo _open('span.faxNumber');
			echo _tag('span.type', __('fax'));
			echo '&nbsp;';
			echo _tag('span.value itemprop="faxNumber"', $renseignements->getFax());
		echo _close('span');
	}

	echo _open('div.email');
		echo _tag('span.type', __('Email'));
		echo '&nbsp;:&nbsp;';
		echo _tag('span.value itemprop="email"', _link('mailto:' . $renseignements->getEmail())->text($renseignements->getEmail()));
	echo _close('div');
echo _close('div');
?>