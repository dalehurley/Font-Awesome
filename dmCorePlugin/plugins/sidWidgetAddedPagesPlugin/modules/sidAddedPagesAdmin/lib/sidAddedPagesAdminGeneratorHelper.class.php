<?php

/**
 * sidAddedPagesAdmin module helper.
 *
 * @package    ec-tenor
 * @subpackage sidAddedPagesAdmin
 * @author     Your name here
 * @version    SVN: $Id: helper.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sidAddedPagesAdminGeneratorHelper extends BaseSidAddedPagesAdminGeneratorHelper
{
    public function executeSortTree(sfWebRequest $request)
	{
        parent::executeSortTree();
        $this->getService('filesystem')->sf('dm:sync-pages');
        
    }
}
