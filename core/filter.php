<?php

class Filter {

  private $type;
  private $before;
  private $cached_method;

  public function __construct($type) {
    $this->type = $type;
    $this->before = array();
  }

  public function add($func, $params=array()) {
    $this->cached_method = $func;
    if($this->isBeforeFilter()) {
      $this->before[$func]= array(
        'method' => $func,
        'params' => $params,
        'actions' => null
      );
    }

    return $this;
  }

  public function only($actions=array()) {
    if($this->isBeforeFilter()) {
      $this->before[$this->cached_method]['actions'] = $actions;
    }
  }

  public function getBeforeFilters() {
    return $this->before;
  }

  public function isBeforeFilter() {
    return ($this->type === 'before') ? true : false;
  }

}
