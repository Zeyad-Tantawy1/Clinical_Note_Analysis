<?php

class Controller
{
    public function view($view, $data = [])
    {
        $file = VIEWS.$view.'.php';
        require_once($file);
    }

    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
}

?>