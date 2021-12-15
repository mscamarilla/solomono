<?php


namespace Controller;
use Core\Controller;


/**
 * Class ControllerError
 * @package Controller
 */
class ControllerError extends Controller
{
    /**
     *
     */
    public function actionNot_found()
    {

        header('HTTP/1.1 404 Not Found');
        $data = 'Page not found';
        $this->view->render($data);
    }

    /**
     *
     */
    public function actionForbidden()
    {
        header('HTTP/1.1 403 Forbidden');
        $data = 'You don\'t have permission to access this page';
        $this->view->render($data);
    }

    /**
     *
     */
    public function actionView_error()
    {
        header('HTTP/1.1 418 I’m a teapot');
        $data = 'Could not load template file';
        $this->view->render($data);
    }
}