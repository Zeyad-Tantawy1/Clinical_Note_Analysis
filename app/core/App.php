<?php

class App
{
    protected $controller = 'homeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        require_once('../app/controllers/'. $this->controller .'.php');
        $url = $this->prepareURL();
        if(!empty($url))
        {
            if(file_exists('../app/controllers/'. $url[0] .'Controller.php'))
            {
                $this->controller = isset($url[0]) ? $url[0]. "Controller": "homeController";
                unset($url[0]);
            }
        }
        if(isset($url[1]))
        {
            if(method_exists($this->controller, $url[1]))
            {
                $this->method = isset($url[1]) ? $url[1]: "index";
                unset($url[1]);
            }
        }
        $this->params = $url ? array_values($url): [];
        $this->controller = new $this->controller;
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    private function prepareURL()
    {
        $url = $_SERVER['QUERY_STRING'];
        if (isset($url) && !empty($url)) {
            $url = trim($url, '/');
            $url = explode('/', $url);
            if (isset($url[0]) && strpos($url[0], 'url=') === 0) {
                $url[0] = str_replace('url=', '', $url[0]);
            }
            return $url;
        }
        return [];
    }
}

?>