<?php

class mySearchPageDocument extends dmSearchPageDocument {
    // here, override the methods  

    /**
     * Get a page content - Override of dmSearchPageDocument defined in services.yml by : search_document.class:      mySearchPageDocument  
     *
     * @return string the page text content
     */
    protected function getPageContent() {
        if (null !== $this->pageContentCache) {
            return $this->pageContentCache;
        }

        if (sfConfig::get('sf_app') !== 'front') {
            throw new dmException('Can only be used in front app ( current : ' . sfConfig::get('sf_app') . ' )');
        }

        $culture = $this->options['culture'];

        $this->context->setPage($this->page);

        $serviceContainer = $this->context->getServiceContainer();
        $helper = $serviceContainer->get('page_helper');
        $widgetTypeManager = $serviceContainer->get('widget_type_manager');

        $pageView = self::getPageViewQuery()->fetchArray(array($this->page->get('module'), $this->page->get('action')));

        $areaIds = array();
        foreach ($pageView[0]['Areas'] as $area) {
            $areaIds[] = $area['id'];
        }
        $zonesQuery = clone self::getZonesQuery($culture);
        $zones = $zonesQuery
                ->whereIn('z.dm_area_id', $areaIds)
                ->fetchArray();

        sfConfig::set('dm_search_populating', true);

        $this->pageContentCache = '';

        foreach ($zones as $zone) {
            foreach ($zone['Widgets'] as $widget) {
                try {
                    $widget['value'] = isset($widget['Translation'][$culture]['value']) ? $widget['Translation'][$culture]['value'] : '';
                    unset($widget['Translation']);

                    $widgetType = $widgetTypeManager->getWidgetType($widget['module'], $widget['action']);

                    try {
                        $this->pageContentCache .= $serviceContainer
                                ->addParameters(array(
                                    'widget_view.class' => $widgetType->getViewClass(),
                                    'widget_view.type' => $widgetType,
                                    'widget_view.data' => $widget
                                ))
                                ->getService('widget_view')
                                ->renderForIndex();
                    } catch (dmFormNotFoundException $e) {
                        // a form is required but not available, skip this widget
                    }
                } catch (Exception $e) {
                    // pass on errors
                }
            }
        }

        sfConfig::set('dm_search_populating', false);

        unset($areas, $zones, $html, $helper);

        return $this->pageContentCache;
    }

}

