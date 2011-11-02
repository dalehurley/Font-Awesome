<?php
/* Version de base */
if ($sf_user->isAuthenticated()) {
    echo _tag('p', __('You are authenticated as %username%', array('%username%' => $sf_user->getUsername())));
    echo _tag('p', _link('/security/signout')->text(__('Click here to logout')));
    return;
}

echo $form->open('.dm_signin_form action=@signin');

echo _tag('ul.dm_form_elements',
        _tag('li.dm_form_element', $form['username']->label()->field()->error()) .
        _tag('li.dm_form_element', $form['password']->label()->field()->error()) .
        _tag('li.dm_form_element', $form['remember']->field()->error()->label())
);

echo $form->renderHiddenFields();

echo $form->submit(__('Signin'));

echo $form->close();

 

//if ($sf_user->isAuthenticated()) { // utilisateur authentifié : on affiche son nom et le lien de déconnexion
//    echo _tag('div.wrapperLogout', '&nbsp;');
//    echo _tag('div.logout',
//            _tag('span', _link('/security/signout')->set(array('title' => __('Logout')))->text(_tag('span.logout',''))).
//            _tag('span.username', __('Hello %username%', array('%username%' => $sf_user->getUsername())))
//    );
//
//    return;
//} else {
//    // on affiche seulement le lien de connexion
//    //echo _tag('div.logout',
//    //        _tag('span.logout', _link('/security/signin')->text(__('Login')))
//    //);
//
//    // on affiche une div de connexion
//    echo _tag('div.loginLink',
//            _tag('span', _link('#')->set(array('title' => __('Login')))->text(_tag('span.loginLink','')))
//            );
//
//    if ($form['username']->error()!= ''){
//        $affDiv = 'block';
//    } else {
//        $affDiv = 'none';
//    }
//
//    echo _tag('div.login', array('style' => 'display: '.$affDiv),
//    $form->open('.dm_signin_form action=@signin')
//
//    . _tag('span.dm_form_element', $form['username']->label()->field())
//    . _tag('span.dm_form_element', $form['password']->label()->field())
//   // . _tag('span.dm_form_element', $form['remember']->field()->error()->label())
//
//    . $form->renderHiddenFields()
//
////    . $form->submit(__('Signin'))
//    . $form->submit(__('Ok'))
//
//    . _tag('span.dm_form_element', $form['username']->error())
//    . _tag('span.dm_form_element', $form['_csrf_token']->error())
//
////  Version button
////    . _tag('div.submit_wrap',
////                array('style' => 'display: block;'),
////                _tag('a.button',
////                        array('href' => '#', 'onclick' => '$("form.dm_signin_form").submit();'),
////                        _tag('span', __('Ok'))
////                )
////        )
//
//    . $form->close()
//    );
//}

