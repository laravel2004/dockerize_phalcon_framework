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
        $cache = $this->di->get('cache');
        $keySupportingMaterial   = "dashboardSupportingMaterial";
        $keyProject              = "dashboardProject";
        $keyActivityLog          = "dashboardActivityLog";

        if (!$cache->has($keySupportingMaterial)) {
            $supportingMaterial = SupportingMaterial::find(["conditions" => "deleted_at IS NULL"])->count();
            $cache->set($keySupportingMaterial, $supportingMaterial, 600);
        }

        if (!$cache->has($keyProject)) {
            $project = Project::find(["conditions" => "deleted_at IS NULL"])->count();
            $cache->set($keyProject, $project, 600);
        }

        if (!$cache->has($keyActivityLog)) {
            $activityLog = ActivityLog::find(["conditions" => "deleted_at IS NULL"])->count();
            $cache->set($keyActivityLog, $activityLog, 600);
        }

        $supportingMaterial = $cache->get($keySupportingMaterial);
        $project = $cache->get($keyProject);
        $activityLog = $cache->get($keyActivityLog);

        $this->view->title = 'Dashboard';
        $this->view->subtitle = 'Welcome to Admin Dashboard';
        $this->view->routeName = "dashboard";
        $this->view->setVar('project', $project);
        $this->view->setVar('supportingMaterial', $supportingMaterial);
        $this->view->setVar('activityLog', $activityLog);

    }

}

