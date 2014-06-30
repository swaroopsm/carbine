<?php

class Input {

  private $getParams;
  private $postParams;

  public function __construct() {
  
  }

  public function setGetParams($params) {
    $this->getParams = $params;
  }

  public function setPostParams($params) {
    # Guess, will have to make same simple security checks here.
    $this->postParams = $params;
  }

  public function get() {
    return $this->getParams;
  }

  public function post() {
    return $this->postParams;
  }

}
