<?php

class Routes extends Router {

  public function build() {

    # All application routes are defined here
    # To define a GET route use `$this->get()`
    # To define a POST route use `$this->post()`
    # Parameters for both these methods include:
    # - Route Name
    # - Route Path
    # - Controller
    # - Action
    
    $this->get('sample', '/sample/:id/assign/:user', 'HomeController', 'show');
    $this->get('user_show', '/:username', 'HomeController', 'show');

    # This is the Root url. Parameters include:
    # - Route Name
    # - Controller
    # - Action
    $this->setRoot('root', 'HomeController', 'index');

  }

}
