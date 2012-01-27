<?php

class dmChartComponents extends dmAdminBaseComponents
{
  
  public function executeLittle()
  {
    $this->chartKey = $this->name;
    $this->option = $this->options_param;
  }
  
  
}