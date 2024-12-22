<?php

class View
{
    public static function load($viewName, $viewData=[])
    {
        $file = VIEWS.$viewName.'.php';
        if(file_exists($file))
        {
            extract($viewData);
            ob_start();
            require($file);
            ob_end_flush();
        }
        else
        {
            echo "This View: ".$viewName. " Does Not Exist";
        }
    }
}

?>