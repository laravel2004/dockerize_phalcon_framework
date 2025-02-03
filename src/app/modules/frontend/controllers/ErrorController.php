<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

class ErrorController extends \Phalcon\Mvc\Controller
{
    public function show404Action()
    {
        $this->response->setStatusCode(404, 'Not Found'); 
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW); 
        $this->view->pick('errors/show404'); // Adjust the view path as needed
    }
}