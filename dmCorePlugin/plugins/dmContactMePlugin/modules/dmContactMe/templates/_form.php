<?php

// Contact : Form
// Vars : $form
echo _tag('h2.title', 'Contactez nous ...');
if ($sf_user->hasFlash('contact_form_valid')) {
    echo _tag('p.form_valid', __('Thank you, your contact request has been sent.'));
} else {


    // render captcha if enabled
    if ($form->isCaptchaEnabled()) {


        echo '<script type="text/javascript">
       
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
        // . $form['captcha']->field()->error();
    }



//    echo '---------------------------------------';
//    echo $form->open(array('class' => 'contactForm'));
//    echo $form;

//    echo $form['name']->label()->field()->error()->help();
//    echo $form['email']->label()->field()->error()->help();
//    echo $form['body']->label()->field()->error()->help();
//    
//    echo $form->close();
//    echo '---------------------------------------';
// open the form tag with a dm_contact_form css class
    /*
      echo $form->open(array('class' => 'contactForm'));

      echo _tag('fieldset',
      _tag('legend', 'Coordonnées')
      . $form['name']->field()
      . $form['name']->label()
      . $form['name']->error()
      . '<div style="clear:both">'
      . $form['email']->field()->label()->help()->error()
      );

      echo $form['body']->field()->label()->error();
     */

echo '<form action="http://demo-cgp-tenor.com/dev.php/contact" method="post" id="dm_form_1" class="contactForm">
    <ul class="dm_form_elements">
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_name">Nom</label>
  <input type="text" id="dm_contact_me_form_name" name="dm_contact_me_form[name]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_first_name">Prénom</label>
  <input type="text" id="dm_contact_me_form_first_name" name="dm_contact_me_form[first_name]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_function">Fonction</label>
  <input type="text" id="dm_contact_me_form_function" name="dm_contact_me_form[function]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_adresse">Adresse</label>
  <input type="text" id="dm_contact_me_form_adresse" name="dm_contact_me_form[adresse]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_zip_code">Code Postal</label>
  <input type="text" id="dm_contact_me_form_zip_code" name="dm_contact_me_form[zip_code]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_city">Ville</label>
  <input type="text" id="dm_contact_me_form_city" name="dm_contact_me_form[city]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_phone">Téléphone</label>
  <input type="text" id="dm_contact_me_form_phone" name="dm_contact_me_form[phone]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_fax">Fax</label>
  <input type="text" id="dm_contact_me_form_fax" name="dm_contact_me_form[fax]">
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_email">Email</label>
  <input type="text" id="dm_contact_me_form_email" name="dm_contact_me_form[email]"><div class="dm_help_wrap">Votre courriel ne sera jamais publié</div>
</li>
<li class="dm_form_element clearfix">
  <label for="dm_contact_me_form_body">Votre message</label>
  <textarea id="dm_contact_me_form_body" name="dm_contact_me_form[body]" cols="30" rows="4"></textarea>
<input type="hidden" id="dm_contact_me_form_id" name="dm_contact_me_form[id]">
<input type="hidden" id="dm_contact_me_form__csrf_token" value="2c7cb8c05d4f6b50d82578c19f5eb5fb" name="dm_contact_me_form[_csrf_token]"></li>
<li class="dm_form_element"><input type="submit" class="submit" value="Envoyer" disabled="" style="display: block; margin-left: 200px"></li></ul></form>';

    if (!$form->isQaptchaEnabled()) {
        // ajout de http://www.myjqueryplugins.com/QapTcha
        echo '<!-- QapTcha CSS --><link rel="stylesheet" href="/dmContactMePlugin/css/qapTchaJquery.css" type="text/css" />';

        use_javascript('/dmContactMePlugin/jquery/jquery-ui.js');
        use_javascript('/dmContactMePlugin/jquery/QapTcha.jquery.js');

        echo '<div id="QapTcha"></div>';
        echo '
        <script type="text/javascript">
        $(\'#QapTcha\').QapTcha({
        disabledSubmit:true,
        txtLabel:  "' . __("Slide please to submit") . '",
        txtHelp:  "' . __("We ask you to validate your submission in this way to fight against spam") . '",
        txtLock: "' . __("Locked : form cant be submited") . '",
        txtUnlock: "' . __("Unlocked : form can be submited") . '"
        });
        </script>';

        echo _tag('div.submit_wrap', array('style' => 'display: none;'), _tag('a.button', array('href' => '#', 'onclick' => '$("form.contactForm").submit();'), _tag('span', __('Send our message'))
                )
        );
    }

    echo $form->renderHiddenFields();

    if ($sf_user->hasFlash('bad_qaptcha')) {
        echo _tag('p.form_valid', __('Bad captcha'));
    }

// close the form tag
    //echo $form->close();
}