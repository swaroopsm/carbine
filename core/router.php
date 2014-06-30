<?php

abstract class Router {

  protected $routes;
  protected $namedRoutes;
  protected abstract function build();

  public function postInit() {
    $this->namedRoutes = array();
    foreach($this->routes as $key => $value) {
      $this->namedRoutes[$value['name']] = $value;
    }
  }

  public function setRoot($name, $controller, $action) {
    $this->setRoute($name, '\s?', $controller, $action, 'GET');
  }

  public function get($name, $path, $controller, $action) {
    $this->setRoute($name, $path, $controller, $action, 'GET');
  }

  public function post($name, $path, $controller, $action) {
    $this->setRoute($name, $path, $controller, $action, 'POST');
  }

  private function setRoute($name, $path, $controller, $action, $method) {
    $original_path = $path;
    $parts = explode('/', $path);
    $path = '';
    $params = array();
    for($i=0; $i<count($parts); $i++) {
      $part = trim($parts[$i]);
      if($part !== '') {
        if($path === '') {
          $path = '^';
        }
        if(substr($part, 0, 1) === ':') {
          $path .= '([a-zA-Z0-9_-]+)' . '\/';
          $params []= substr($part, 1, strlen($part));
        }
        else {
          $path .= $part . '\/';
        }
      }
    }
    $path .= '?$';
    $this->routes[$path] = array(
      'name' => $name,
      'pattern' => $path,
      'controller' => $controller,
      'action' => $action,
      'params' => $params,
      'method' => $method,
      'path' => $original_path
    );
  }

  public function match($pattern) {
    foreach($this->routes as $key => $value) {
      if(preg_match_all("/$key/", $pattern, $parts)) {
        if($this->requestMethod() !== $value['method']) {
          CarbineException::Error("Route not found. May be the request method you specified is incorrect.");
        }
        $result = $value;
        $params = array();
        $param_values = $this->array_flatten(array_slice($parts, 1, count($parts)-1));
        for($i=0; $i<count($param_values); $i++) {
          $params[$value['params'][$i]] = $param_values[$i];
        }
        $result['params'] = $params;
        $this->routes[$key] = $result;
        $controller = new $result['controller']();
        $controller->setRouteParams($result);
        $controller->setNamedRoutes($this);
        $controller->runBeforeFilters();
        call_user_func_array(array($controller, $result['action']), array($params));
        return;
      }
    }

    CarbineException::Error("Route not found. Is the route configured?");
  }

  public function linkRoute($name, $params=null) {
    return $this->makeRoute($this->namedRoutes[$name], $params);
  }

  private function makeRoute($route, $params=null) {
    $route_params = array();
    $path = $route['path'];
    $patterns = array();
    $replacements = array();
    if(count($route['params']) !== count($params)) {
      CarbineException::Error('This route requires some paramters to be passed');
    }
    for($i=0; $i<count($route['params']); $i++) {
      $param  = $route['params'][$i];
      if(!$params[$param]) {
        CarbineException::Error('Parameter `'.$param.'` is missing');
      }
      $route_params[$param] = $params[$param];
      $patterns []= '/:' . $param . '/';
      $replacements []= $params[$param];
    }

    return preg_replace($patterns, $replacements, $path);
  }

  # This flattens array for only one level. Multi-levels not yet supported.
  private function array_flatten($array) {
    $newarray = array();
    for($i=0; $i<count($array); $i++) {
      $str = str_replace("/", "", $array[$i][0]);
      $newarray []= $str;
    }

    return $newarray;
  }

  private function requestMethod() {
    return $_SERVER['REQUEST_METHOD'];
  }

}
