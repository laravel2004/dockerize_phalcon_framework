<?php
declare(strict_types=1);

namespace Erp_rmi\Modules\Frontend\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->title = 'Dashboard';
        $this->view->subtitle = 'Welcome to Admin Dashboard';
        $this->view->routeName = "dashboard";
    }

}

