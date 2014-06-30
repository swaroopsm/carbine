<?php

class Input {

  private $getParams;

  public function __construct() {
  
  }

  public function setGetParams($params) {
    $this->getParams = $params;
  }

  public function get() {
    return $this->getParams;
  }

}
