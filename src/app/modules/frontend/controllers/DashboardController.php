<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivityLog;
use Erp_rmi\Modules\Frontend\Models\Project;
use Erp_rmi\Modules\Frontend\Models\SupportingMaterial;
use Phalcon\Mvc\Controller;

class DashboardController extends Controller
{

    public function indexAction()
    {
        $project = Project::find(["conditions" => "deleted_at IS NULL"])->count();
        $supportingMaterial = SupportingMaterial::find(["conditions" => "deleted_at IS NULL"])->count();
        $activityLog = ActivityLog::find(["conditions" => "deleted_at IS NULL"])->count();
        $this->view->title = 'Dashboard';
        $this->view->subtitle = 'Welcome to Admin Dashboard';
        $this->view->routeName = "dashboard";
        $this->view->setVar('project', $project);
        $this->view->setVar('supportingMaterial', $supportingMaterial);
        $this->view->setVar('activityLog', $activityLog);

    }

}

