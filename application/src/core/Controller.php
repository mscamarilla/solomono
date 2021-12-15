<?php


namespace Core;


/**
 * Class Controller
 * @package Core
 */
class Controller
{
    /**
     * @var
     */
    protected $view;
    /**
     * @var array|string
     */
    protected $get;
    /**
     * @var array|string
     */
    protected $post;

    /**
     * Controller constructor.
     * @param $view
     */
    function __construct($view)
    {
        $view_name = str_replace('Controller', 'View', implode('', array_slice(explode('\\', get_class($this)), -1)));

        $this->view = $view;
        $this->view->setViewName($view_name . '.tpl');
        $this->get = $this->clean($_GET);
        $this->post = $this->clean($_POST);


    }

    /**
     * @param $model_alias
     */
    protected function loadModel($model_alias)
    {
        $model_name = $this->renameModel($model_alias);

        $this->$model_alias = new $model_name;

    }

    /**
     * @param $language_alias
     * @return mixed
     */
    protected function loadLanguage($language_alias)
    {
        $language_name_parts = explode('_', $language_alias);
        $language_name_array = [];
        foreach ($language_name_parts as $value) {
            $language_name_array[] = ucfirst($value);
        }

        $language_name = implode('', $language_name_array);
        $path = realpath(dirname(__FILE__) . '/../language');

        return include($path . '/' . $language_name . '.php');
    }

    /**
     * @param $model_alias
     * @return string
     */
    protected function renameModel($model_alias) :string
    {
        $model_name_parts = explode('_', $model_alias);
        $model_name_array = [];
        foreach ($model_name_parts as $value) {
            $model_name_array[] = ucfirst($value);
        }

        return '\Model\\' . implode('', $model_name_array);

    }

    /**
     * Trimming data for $_GET and $_POST
     * @param mixed $data
     * @return array|string
     */
    protected function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset ($data[$key]);
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }
        return $data;
    }

}