<?php

namespace Core;

/**
 * Class View
 * @package Core
 */
class View implements ViewInterface
{

    /**
     * @var
     */
    protected $view_name;

    /**
     * @param $view_name
     */
    public function setViewName($view_name)
    {
        $this->view_name = $view_name;

    }

    /**
     * @param null $data
     */
    public function render($data = null)
    {
        if(is_file('src/view/' . $this->view_name)) {
            include 'src/view/' . $this->view_name;
        } else{
            header(sprintf("Location: %s", 'index.php?route=error/view_error'));
        }
    }
}