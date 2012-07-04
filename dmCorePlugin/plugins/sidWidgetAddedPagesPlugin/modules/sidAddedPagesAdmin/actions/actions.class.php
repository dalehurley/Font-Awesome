<?php

require_once dirname(__FILE__).'/../lib/sidAddedPagesAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sidAddedPagesAdminGeneratorHelper.class.php';

/**
 * sidAddedPagesAdmin actions.
 *
 * @package    ec-tenor
 * @subpackage sidAddedPagesAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sidAddedPagesAdminActions extends autoSidAddedPagesAdminActions
{
    public function executeSortTree(sfWebRequest $request)
	{
        parent::executeSortTree($request);
        $this->getService('filesystem')->sf('dm:sync-pages');
        
    }
}
