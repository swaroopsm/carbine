<?php

abstract class Controller {

  public function __construct() {
    $this->beforeFilter = new Filter('before');
    $this->input = new Input();
    $this->view = new Tower();
    $this->setLayout('default');
  }

  public function input() {
    return $_POST;
  }

  public function setNamedRoutes($router) {
    $this->view->set('router', $router);
  }

  public function render() {
    $this->validate();
    $this->view->setTemplate($this->getView());
    $this->view->render();
  }

  public function setViewDirectory($directory) {
    $this->viewDirectory = VIEWS . $directory;
  }

  public function setRouteParams($params) {
    $this->routeParams = $params;
    $this->input->setGetParams($this->routeParams['params']);
  }

  public function getView() {
    return $this->viewDirectory . '/' . $this->routeParams['action'] . '.php';
  }

  public function runBeforeFilters() {
    foreach($this->beforeFilter->getBeforeFilters() as $key => $value) {
      if(in_array($this->routeParams['action'], $value['actions'])) {
        call_user_func_array(array($this, $value['method']), $value['params']);
      }
    }
  }

  protected function setLayout($layout) {
    $this->layout = LAYOUT . $layout . '.php';
    $this->view->setLayout($this->layout);
  }

  protected function validate() {
    $validators = array();
    $validators['viewDirectory'] = $this->checkValidator($this->viewDirectory);

    foreach($validators as $key => $value) {
      if(!$value) {
        CarbineException::Error('Looks like something is missing. `' . $key . '`');
      }
    }
  }

  private function checkValidator($field) {
    if(!$field) {
      return false;
    }

    return true;
  }

  private $action;
  private $viewDirectory;
  private $routeParams;
  protected $view;
  protected $layout;
  protected $beforeFilter;
  protected $input;

}
