<?php

// gestion du message dépendant du hostname
$info = '';
$ipMessages = sfConfig::get('app_link-login_ips-message');
foreach ($ipMessages as $ip => $message) {
  if ($_SERVER["SERVER_ADDR"] == $ip){
    $info = $message;
  }
}

// acces autorisé pour les ips-allowed
$accesGranted = false;
$ips = sfConfig::get('app_controllers-access_ips-allowed');
foreach ($ips as $key => $ip) {
    if (substr($_SERVER['REMOTE_ADDR'], 0, strlen($ip)) == $ip) {
        $accesGranted = true;
        break;
    }
}

echo _open('div.dm.dm_auth');

echo $_SERVER["SERVER_ADDR"];

if ($info == '' || $accesGranted){ // on n'accède au formulaire de login que si l'on fait partie des ips-allowed ou si l'info (ips-message) liée à l'ip du serveur qui execute (dans le config/app.yml du Core) est nulle
  echo _tag('h1.site_name', dmConfig::get('site_name'));

  echo _tag('div.message',
    $form->open('.dm_form.list.little.clearfix action="@signin"').
      _tag('ul',
        _tag('li.dm_form_element.clearfix',
          $form['username']->error()->label(__('Username'))->field()
        ).
        _tag('li.dm_form_element.clearfix',
          $form['password']->error()->label(__('Password'))->field()
        )
      ).
      $form->renderHiddenFields().
      $form->submit(__('Signin'), '.mt10').
    '</form>'
  );
} else {
  echo _tag('h1.site_name', dmConfig::get('site_name'));
  echo _tag('div.info', $info);
}

echo _close('div');

//echo _link('http://diem-project.org/')->text('Diem CMF CMS for symfony')->set('.generator_link');

?>
<script type="text/javascript">document.getElementById('signin_username').focus();</script>