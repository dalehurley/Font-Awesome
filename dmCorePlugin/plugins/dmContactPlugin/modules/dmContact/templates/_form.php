<?php
// Contact : Form
// Vars : $form
 
$html = '';
echo _tag('h2', array('class' => 'title'), '{{Nom_page_contact}}');

//titre du contenu
//$html = get_partial('global/titleWidget', array('title' => __('ContactUs'), 'isContainer' => true));
echo _tag('p',  '{{Accueil_contact}}');
//ajout message de validation
if($sf_user->hasFlash('contact_form_valid')) $html.= _tag('p.form_valid', __('Thank you, your contact request has been sent.'));

//ajout du javascript pour le captcha
if($form->isCaptchaEnabled()) {
	$html.= '<script type="text/javascript">
       
        var RecaptchaOptions = {  
            custom_translations : {
                instructions_visual : "' . __('Enter the words above') . ':",
                instructions_audio : "' . __('Enter the numbers you hear') . ':",
                play_again : "' . __('Get another CAPTCHA') . '",
                cant_hear_this : "' . __('Download as MP3') . '",
                visual_challenge : "' . __('Get an image CAPTCHA') . '",
                audio_challenge : "' . __('Get an audio CAPTCHA') . '",
                refresh_btn : "' . __('Get another CAPTCHA') . '",
                help_btn : "' . __('Help') . '",
                incorrect_captcha_sol   : "' . __('Help') . '",
                incorrect_try_again : "' . __('Incorrect. Please try again.') . '", 
            },

            //theme : \'custom\',
            theme : \'clean\',
            //custom_theme_widget: \'recaptcha_widget\',
            lang : \'' . $sf_user->getCulture() . '\',
        };
    </script>';
}

//open the form tag with a dm_contact_form css class
$html.= $form->open();

//ouverture du listing
$html.= _open('ul.dm_form_elements');

$html.= $form['title']->renderRow().
		$form['name']->renderRow().
		$form['firstname']->renderRow().
		$form['function']->renderRow().
		$form['adresse']->renderRow().
		$form['postalcode']->renderRow().
		$form['ville']->renderRow().
		$form['email']->renderRow().
		$form['phone']->renderRow().
		$form['fax']->renderRow().
		$form['body']->renderRow();

// render captcha if enabled
//if($form->isCaptchaEnabled()) $html.= $form['captcha']->label('Captcha', 'for=false')->field()->error();
if($form->isCaptchaEnabled()) $html.= $form['captcha']->renderRow();

//insertion du bouton de validation
// $html.= _tag('div.submit_wrap', $form->submit(__('Send')));
$html.= _tag('li.dm_form_element.clearfix', $form->submit(__('Send')));

//fermeture du listing
$html.= _close('ul.dm_form_elements');

//affichage des champs cachés
$html.= $form->renderHiddenFields();

// close the form tag
$html.= $form->close();

//affichage html en sortie
echo $html;