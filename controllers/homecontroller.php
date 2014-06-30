<?php

class HomeController extends Controller {

  public function __construct() {
    parent::__construct();
    $this->setViewDirectory('home');

    #Before Filter
    $this->beforeFilter->add('authenticate')->only(array('show'));
  }

  public function index() {
    $this->view->set('name', 'Carbine');
    $this->view->set('desc', 'The most simplistic PHP Framework');

    $this->render();
  }

  public function show($params) {
    $this->view->set('username', $params['username']);

    $this->render();
  }

  public function full() {
    print_r($this->input());
  }

  public function authenticate() {
    $input = $this->input->get();
    if($input['username'] !== 'striker') {
      CarbineException::Error('Permission Denied');
    }
  }

}
