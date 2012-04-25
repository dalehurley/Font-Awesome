<?php

class BasedmUserAdminActions extends autodmUserAdminActions {
    public function executeMyAccount(sfWebRequest $request) {
        $this->forward404Unless($this->dmUser = $this->getUser()->getDmUser());
        $this->form = new DmUserAdminMyAccountForm($this->dmUser);
        if ($request->hasParameter($this->form->getName()) && $this->form->bindAndValid($request)) {
            $this->form->save();
            $this->getUser()->logInfo('Your modifications have been saved');
            
            return $this->redirectBack();
        }
    }
    public function executeSignin(dmWebRequest $request) {
        // lioshi: autologin - login de l'admin par reception de la variable key qui contient l'ip du serveur appelant et le nom de domaine du site
        // la fonction de hachage intègre la date au jour près près : date("Y-m-d"), le lien est donc valable une journée
        // l'emettteur est par IP du serveur appelant et par le nom de domaine
        // 
        // 
        // l'émetteur du lien d'autologin doit présenter l'id et la key de la form
        // http://site.com/admin.php?key=YYYYY
        // la key doit correspondre à sha1(date("Y-m-d"). $ip . ndd_du_site)
        // où ndd_du_site est le nom de domaine complet sans "http://", exemple "site.com"
        // où $ip fait partie du tableau app_link-login_ips-allowed de config/app.yml du Core
        // 
        if (sfConfig::get('app_link-login_active')) {

                // $pathArray = $request->getPathInfoArray();
                // $remoteServer = $pathArray['REMOTE_ADDR'];
                $ips = sfConfig::get('app_link-login_ips-allowed');
                
                foreach ($ips as $key => $ip) {
                    $calculatedKey = sha1(date("Y-m-d"). $ip . dmConfig::get('site_ndd'));
                    // lioshi: ajout en session pour déboggage eventuel de l'accès
                    //$this->getUser()->setAttribute('AutoLoginKey-'.$ip,$calculatedKey);

                    if ($request->getParameter('key') == $calculatedKey) {
                        // le nom du user spécifié dans app.yml
                        $webClient = dmDb::table('dm_user')->findOneByUsername(sfConfig::get('app_link-login_user'));
                        // on log l'utilisatueur courrant avec l'user trouvé
                        $this->getUser()->signIn($webClient, false);
                           
                        return $this->redirect('@homepage');
                        break;
                }
            }
        }
        if ($this->getUser()->isAuthenticated()) {
            
            return $this->redirect('@homepage');
        }
        $this->setLayout(realpath(dirname(__FILE__) . '/..') . '/templates/layout');
        if ($request->getParameter('skip_browser_detection')) {
            $this->getService('browser_check')->markAsChecked();
        } elseif (!$this->getService('browser_check')->check()) {
            
            return 'Browser';
        }
        $this->form = new DmSigninAdminForm();
        if ($request->isMethod('post')) {
            $this->form->bindRequest($request);
            if ($this->form->isValid()) {
                $this->getUser()->signin($this->form->getValue('user') , $this->form->getValue('remember'));
                if ($this->getUser()->can('admin')) {
                    $redirectUrl = $this->getUser()->getReferer($request->getReferer());
                    $this->redirect($redirectUrl ? $redirectUrl : '@homepage');
                } else {
                    try {
                        $this->redirect($this->getService('script_name_resolver')->get('front'));
                    }
                    catch(dmException $e) {
                        // user can't go in admin, and front script_name can't be found.
                        $this->redirect('@homepage');
                    }
                }
            }
        } else {
            if ($request->isXmlHttpRequest()) {
                $this->getResponse()->setHeaderOnly(true);
                $this->getResponse()->setStatusCode(401);
                
                return sfView::NONE;
            }
            // if we have been forwarded, then the referer is the current URL
            // if not, this is the referer of the current request
            $this->getUser()->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());
            $module = sfConfig::get('sf_login_module');
            if ($this->getModuleName() != $module) {
                
                return $this->redirect($module . '/' . sfConfig::get('sf_login_action'));
            }
            $this->getResponse()->setStatusCode(401);
        }
    }
    public function executeSignout($request) {
        $this->getUser()->signOut();
        $signoutUrl = sfConfig::get('dm_security_success_signout_url', $request->getReferer());
        $this->redirect('' != $signoutUrl && $signoutUrl !== $request->getReferer() ? $signoutUrl : '@homepage');
    }
    public function executeSecure() {
        $this->setLayout(realpath(dirname(__FILE__) . '/..') . '/templates/layout');
        $this->getResponse()->setStatusCode(403);
    }
    public function executePassword() {
        throw new sfException('This method is not yet implemented.');
    }
    public function validateEdit() {
        if ($this->getRequest()->isMethod('post') && !$this->getRequestParameter('id')) {
            if ($this->getRequestParameter('dm_user[password]') == '') {
                $this->getRequest()->setError('dm_user{password}', $this->getContext()->getI18N()->__('Password is mandatory'));
                
                return false;
            }
        }
        
        return true;
    }
    public function executeDelete(sfWebRequest $request) {
        try {
            
            return parent::executeDelete($request);
        }
        catch(dmRecordException $e) {
            $this->getUser()->logError($e->getMessage());
            $this->redirectBack();
        }
    }
}
