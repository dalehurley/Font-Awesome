<?php

class dmWebDebugPanelMemory extends sfWebDebugPanel
{
  public function getTitle()
  {
    $totalMemory = sprintf('%.1f', (memory_get_peak_usage(true) / 1024 / 1024));

    return '<a href="#"><img src="'.$this->webDebug->getOption('image_root_path').'/memory.png" alt="Memory" /> '.$totalMemory.' MB</a>';
  }

  public function getPanelTitle()
  {
  }

  public function getPanelContent()
  {
  }
}