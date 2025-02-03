<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivityLog;
use Erp_rmi\Modules\Frontend\Models\Plot;
use Erp_rmi\Modules\Frontend\Models\SupportingMaterial;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ExportController extends Controller
{
    public function indexAction()
    {
        $search = $this->request->getQuery("search", "string", "");
        $query = Plot::query();

        if (!empty($search)) {
            $query->where("code LIKE :search:", ["search" => "%" . $search . "%"]);
        }

        $plots = $query->execute();

        $this->view->setVar('plots', $plots);

        $this->view->title = 'Export Data';
        $this->view->subtitle = 'Export Data Activity Log and Supporting Material';
        $this->view->routeName = "export";
    }

    public function costAction($plotId)
    {
        $startDate = $this->request->getQuery("start_date", "string", "");
        $endDate = $this->request->getQuery("end_date", "string", "");

        $activityLogs = ActivityLog::find([
            "conditions" => "plot_id = :plot_id: AND start_date BETWEEN :start_date: AND :end_date:",
            "bind" => [
                "plot_id" => $plotId,
                "start_date" => $startDate,
                "end_date" => $endDate
            ],
            "with" => ["activitySetting"],
            "order" => "created_at DESC"
        ]);

        $supportingMaterial = SupportingMaterial::find([
            "conditions" => "plot_id = :plot_id: AND date BETWEEN :start_date: AND :end_date:",
            "bind" => [
                "plot_id" => $plotId,
                "start_date" => $startDate,
                "end_date" => $endDate
            ],
            "order" => "created_at DESC",
            "with" => ["material"]
        ]);

        $plotCode = Plot::findFirst($plotId)->code;

        $this->view->plotCode = $plotCode;

        $startDate = $this->request->getQuery("start_date", "string");
        $endDate = $this->request->getQuery("end_date", "string");
        $this->view->startDate = $startDate;
        $this->view->endDate = $endDate;

        $this->view->routeName = "export";
        $this->view->title = 'Export Data';
        $this->view->subtitle = 'Export Data Activity Log and Supporting Material';
        $this->view->setVar('activityLogs', $activityLogs);
        $this->view->setVar('supportingMaterials', $supportingMaterial);
    }



    public function createAction()
    {
        //
    }

    public function editAction($id)
    {
        //
    }

    public function saveAction()
    {

    }



    public function deleteAction($id)
    {

    }

}