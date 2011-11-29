<?php

// Contact : Form
// Vars : $form
echo _tag('h2.title', 'Contactez nous ...');

if ($sf_user->hasFlash('contact_form_valid')) {
    echo _tag('p.form_valid', __('Thank you, your contact request has been sent.'));
} else {

    // open the form tag with a dm_contact_form css class
    echo $form->open('.contact_form');

    echo _tag('ul.dm_form_elements', $form['title']->renderRow() .
            $form['name']->renderRow() .
            $form['firstname']->renderRow() .
            $form['function']->renderRow() .
            $form['adresse']->renderRow() .
            $form['postalcode']->renderRow() .
            $form['ville']->renderRow() .
            $form['email']->renderRow() .
            $form['phone']->renderRow() .
            $form['fax']->renderRow() .
            $form['body']->renderRow()
    );



// affichage du captcha (Qaptcha)
    if ($form->isQaptchaEnabled()) {
        // ajout de http://www.myjqueryplugins.com/QapTcha

        use_stylesheet('/dmContactMePlugin/css/qapTchaJquery.css');

        use_javascript('/dmCorePlugin/lib/jquery-ui/js/source/jquery.ui.core.js');
        use_javascript('/dmCorePlugin/lib/jquery-ui/js/source/jquery.ui.widget.js');
        use_javascript('/dmCorePlugin/lib/jquery-ui/js/source/jquery.ui.mouse.js');
        use_javascript('/dmCorePlugin/lib/jquery-ui/js/source/jquery.ui.position.js');
        use_javascript('/dmCorePlugin/lib/jquery-ui/js/source/jquery.ui.draggable.js');

        use_javascript('/dmContactMePlugin/jquery/QapTcha.jquery.js');

        echo '<div class="QapTcha"></div>';
        echo '
        <script type="text/javascript">
          $(document).ready(function(){
              $(\'.QapTcha\').QapTcha({
              txtLock: "' . __("Locked : form cant be submited") . '",
              txtUnlock: "' . __("Unlocked : form can be submited") . '"
            });
          });
        </script>';

        if ($sf_user->hasFlash('bad_qaptcha')) {
            echo _tag('p.form_valid', __('Bad captcha'));
        }
    }

// affichage du captcha (reCaptcha)
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
    </script>'
        . $form['captcha']->field()->error();
    }

    // render hidden fields like the CSRF protection  
    echo $form->renderHiddenFields();

// change the submit button text  
    echo $form->submit(__('Send our message'));

// close the form tag  
    echo $form->close();
}