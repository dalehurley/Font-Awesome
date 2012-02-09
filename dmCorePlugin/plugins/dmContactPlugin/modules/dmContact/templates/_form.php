<?php
// Contact : Form
// Vars : $form

if($sf_user->hasFlash('contact_form_valid'))
{
  echo _tag('p.form_valid', __('Thank you, your contact request has been sent.'));
}

// open the form tag with a dm_contact_form css class
echo $form->open();


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

// render captcha if enabled
if($form->isCaptchaEnabled())
{

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

  echo $form['captcha']->label('Captcha', 'for=false')->field()->error();

}

echo $form->renderHiddenFields();

// change the submit button text
echo _tag('div.submit_wrap', $form->submit(__('Send')));

// close the form tag
echo $form->close();  