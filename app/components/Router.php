<?php

class Router
{
    const DEFAULT_ROUTES_FILE = APP . "/config/routes.json";

    private $routes = [];

    public function __construct($filename = null)
    {
        if(is_file($filename)&&substr($filename, strlen($filename-5),5)===".json") {
            // TODO: valiate other routers file and use it
        } else {
            $filename = self::DEFAULT_ROUTES_FILE;
        }
        $json_string = file_get_contents($filename);
        $this->routes = json_decode($json_string);
    }

    public function run($url) {
        $result = false;

        $url = trim($url, '/');
        $url = strpos($url, '?')==0?$url:substr($url, 0, strpos($url, '?'));
        $urlParams = $this->parseUrl($url, $this->routes);

        //var_dump($urlParams);

        if (isset($urlParams['controller'])&&isset($urlParams['action'])) {
            $controller = $urlParams['controller'];
            $action = $urlParams['action'];

            unset($urlParams['controller']);
            unset($urlParams['action']);

            $controllerName = ucfirst($controller) . 'Controller';
            $actionName = 'action' . ucfirst($action);
            $parameters = $urlParams;

            $controllerFile = APP . '/controllers/' .
                $controllerName . '.php';
            if (file_exists($controllerFile)) {
                include_once($controllerFile);
                $controllerObject = new $controllerName;
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
            }
        }
        if(!$result) {
            echo '404. Page not found!';
        }

        return $result;
    }

    private function parseUrl(&$url, $routes, &$result = []) {
        $url = trim($url, '/');
        $needlePos = strpos($url, '/');
        if($needlePos==0) {
            $needlePos = strlen($url);
            $section = $url;
        } else {
            $section = substr($url, 0, $needlePos);
        }

        foreach ($routes as $route) {
            $pattern = "~^" . $route->match . "$~";
            //echo "$route->name ($pattern => $route->value): section '$section'<br/>";
            if (preg_match($pattern, $section)) {
                $result[$route->name] = preg_replace($pattern, $route->value, $section);
                $url = substr($url, $needlePos);
                if (isset($route->nodes)) {
                    $result = array_merge($result, $this->parseUrl($url, $route->nodes, $result));
                }
            } elseif ($route->match === 0) {
                if (isset($route->nodes)) {
                    $result[$route->name] = $route->value;
                    $newRes = $this->parseUrl($url, $route->nodes, $result);
                    $result = array_merge($result, $newRes);
                } elseif($section==="") {
                    $result[$route->name] = $route->value;
                }
            } else {
                continue;
            }
            //var_dump($result);
            //echo " ___________ url: $url<br/>";
            if($url===""&&isset($result['controller'])&&isset($result['action'])) {
                break;
            }
        }
        return $result;
    }
}