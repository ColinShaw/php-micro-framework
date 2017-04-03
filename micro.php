<?php

class Micro
{
    private $include_paths = [];
    private $request;
    private $routes;

    public function __construct()
    {
        $this->header = ['Content-Type: application/json'];
        $this->request = urldecode($_SERVER['REQUEST_URI']);
        $this->load();
        $this->init();
    }

    private function load()
    {
        $cwd = getcwd();
        $paths = get_include_path();
        foreach ($this->include_paths as $path) 
        {
            $paths .= ":{$cwd}/{$path}";
        }
        set_include_path($paths);
        spl_autoload_extensions('.php');
        spl_autoload_register();
    }

    private function init()
    {
        $this->routes = [];
    }

    private function resp($response)
    {
        foreach ($this->header as $h) 
        {
            header($h);
        }
        echo $response;
    }

    public function go()
    {
        foreach ($this->routes as $regex => $callback) 
        {
            if (preg_match($regex, $this->request, $params)) 
            {
                array_shift($params);
                $result = call_user_func_array($callback, array_values($params));
                $this->resp($result);
            }
        }
    }
}

(new Micro())->go();
