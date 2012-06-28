<?php // Vars: $form

if (!$error){   // if 1 its only the submit button

    echo _tag('h2.title',$name);
    echo $description;

    // message de succes
    if($sf_user->hasFlash('sid_contact_form_valid')) {
        echo _tag('p.form_valid', __('Thank you, your contact request has been sent.'));
    } else {
        //ajout du javascript pour le captcha
        if($form->isCaptchaEnabled()) {
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
        }
    	echo $form;
    }

} else {
	echo debugTools::infoDebug(
        $error
    , 'warning');
}





