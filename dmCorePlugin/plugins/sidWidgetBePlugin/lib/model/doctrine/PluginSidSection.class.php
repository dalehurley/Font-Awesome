<?php

/**
 * PluginSidSection
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginSidSection extends BaseSidSection {

    public function show_rubrique_section() {

        // on récupère le name de la page de la rubrique, plutot que le title de la rubrique, ou section...
        $sectionPage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('section', 'show', $this->id))
                ->limit(1)
                ->execute();

        $rubriquePage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('rubrique', 'show', $this->getRubrique()->id))
                ->limit(1)
                ->execute();

        return $rubriquePage[0]->name . ' - ' . $sectionPage[0]->name;
    }

    public function show_section_rubrique() {

        // on récupère le name de la page de la rubrique, plutot que le title de la rubrique, ou section...
        $sectionPage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and p.action=? and p.record_id=?', array('section', 'show', $this->id))
                ->limit(1)
                ->execute();

        $rubriquePage = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and p.action=? and p.record_id=?', array('rubrique', 'show', $this->getRubrique()->id))
                ->limit(1)
                ->execute();



        return $sectionPage[0]->name . ' - ' . $rubriquePage[0]->name;
//        return  $sectionPage[0]->name;
    }

}