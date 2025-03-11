<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\Payroll;
use Erp_rmi\Modules\Frontend\Models\WorkerData;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class PayrollController extends Controller
{
    public function indexAction()
    {
        $this->view->title = 'Manage Payroll';
        $this->view->subtitle = 'List of Payroll';
        $this->view->routeName = "payroll";

        $status = $this->request->getQuery("status", "string", null);
        $workerDataId = $this->request->getQuery("worker_data_id", "int", null);
        $dateRange = $this->request->getQuery("date_range", "string", null);

        $conditions = [];
        $bindParams = [];

        if (!empty($dateRange)) {
            $dateRange = explode(" - ", $dateRange);
            $dateStart = date("Y-m-d", strtotime($dateRange[0]));
            $dateEnd = date("Y-m-d", strtotime($dateRange[1]));
            $conditions[] = "date BETWEEN :date_start: AND :date_end:";
            $bindParams["date_start"] = $dateStart;
            $bindParams["date_end"] = $dateEnd;
        }

        if (!empty($status)) {
            $conditions[] = "status = :status:";
            $statusPaid = 0;
            if ($status == "unpaid") {
                $statusPaid = 0;
            } else {
                $statusPaid = 1;
            }
            $bindParams["status"] = $statusPaid;
        }

        if (!empty($workerDataId)) {
            $conditions[] = "worker_data_id = :worker_data_id:";
            $bindParams["worker_data_id"] = $workerDataId;
        }

        $queryParams = ["order" => "created_at DESC"];

        if (!empty($conditions)) {
            $queryParams["conditions"] = implode(" AND ", $conditions);
            $queryParams["bind"] = $bindParams;
        }

        $numberPage = $this->request->getQuery("page", "int", 1);

        $paginator = new PaginatorModel([
            "model"  => "Erp_rmi\Modules\Frontend\Models\Payroll",
            "parameters" => $queryParams,
            "limit"  => 10,
            "page"   => $numberPage
        ]);

        $worker = WorkerData::find([
            'conditions' => 'deleted_at IS NULL',
            'order' => 'name ASC'
        ]);

        $this->view->setVar('worker', $worker);

        $page = $paginator->paginate();
        $page->before = ($page->current > 1) ? $page->current - 1 : null;
        $page->next = ($page->current < ceil($page->total_items / $page->limit)) ? $page->current + 1 : null;
        $page->total_pages = ceil($page->total_items / $page->limit);

        $this->view->setVar('page', $page);
        $this->view->setVar('status', $status);
        $this->view->setVar('workerDataId', $workerDataId);
    }

    public function updateAction()
    {
        try {
            $id = $this->request->getPost("id", "int", null);

            if (is_null($id)) {
                throw new \Exception("ID is required");
            }

            $payroll = Payroll::findFirstById($id);

            if (!$payroll) {
                throw new \Exception("Payroll not found");
            }

            $payroll->status = 1;

            if (!$payroll->save()) {
                throw new \Exception("Failed to update payroll");
            }

            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Payroll has been updated'
            ]);
        }
        catch (\Exception $e) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function arrayUpdateAction()
    {
        try {
            $payrollId = $this->request->getPost('payroll_id', 'array', []);
            $this->db->begin();
            foreach ($payrollId as $id) {
                $payroll = Payroll::findFirstById($id);
                if ($payroll) {
                    $payroll->status = 1;
                    $payroll->save();
                }
            }

            $this->db->commit();
            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Payroll has been updated'
            ]);
        }
        catch (\Exception $e) {
            $this->db->rollback();
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detailAction($id)
    {
        $payroll = Payroll::findFirstById($id);

        if (!$payroll) {
            $this->flashSession->error("Payroll not found");
            return $this->response->redirect("payroll");
        }

        $this->view->title = 'Detail Payroll';
        $this->view->subtitle = 'Detail of Payroll';
        $this->view->routeName = "payroll";
        $terbilangTotal = $this->terbilang($payroll->total_cost);
        $this->view->setVar('terbilangTotal', $terbilangTotal);

        $this->view->setVar('data', $payroll);
    }

    public function reportAction()
    {
        $status = $this->request->getQuery("status", "int", null);
        $workerDataId = $this->request->getQuery("worker_data_id", "int", null);
        $dateRange = $this->request->getQuery("date_range", "string", null);

        $conditions = [];
        $bindParams = [];

        if (!empty($dateRange)) {
            $dateRange = explode(" - ", $dateRange);
            $dateStart = date("Y-m-d", strtotime($dateRange[0]));
            $dateEnd = date("Y-m-d", strtotime($dateRange[1]));
            $conditions[] = "date BETWEEN :date_start: AND :date_end:";
            $bindParams["date_start"] = $dateStart;
            $bindParams["date_end"] = $dateEnd;
        }

        if (!empty($status)) {
            $conditions[] = "status = :status:";
            $bindParams["status"] = $status;
        }

        if (!empty($workerDataId)) {
            $conditions[] = "worker_data_id = :worker_data_id:";
            $bindParams["worker_data_id"] = $workerDataId;
        }

        $queryParams = [];

        if (!empty($conditions)) {
            $queryParams["conditions"] = implode(" AND ", $conditions);
            $queryParams["bind"] = $bindParams;
        }

        $payroll = Payroll::find($queryParams);

        $longWorker = 0;
        $wideTotal = 0;
        $totalCost = 0;

        foreach ($payroll as $key => $value) {
            $longWorker += $value->unit;
            $wideTotal += $value->ActivityLog->plot->wide;
            $totalCost += $value->total_cost;
        }

        $terbilangTotal = $this->terbilang($totalCost);

        $this->view->title = 'Report Payroll';
        $this->view->subtitle = 'Report of Payroll';
        $this->view->routeName = "payroll";
        $this->view->setVar('longWorker', $longWorker);
        $this->view->setVar('wideTotal', $wideTotal);

        $this->view->setVar('data', $payroll);
        $this->view->setVar('totalCost', $totalCost);
        $this->view->setVar('terbilangTotal', $terbilangTotal);
        $this->view->setVar('dateRange', $dateRange);
    }

    private function terbilang($angka) {
        $angka = abs($angka);
        $bilangan = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        if ($angka < 12) {
            return " " . $bilangan[$angka];
        } elseif ($angka < 20) {
            return $this->terbilang($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            return $this->terbilang(floor($angka / 10)) . " Puluh" . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            return " Seratus" . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $this->terbilang(floor($angka / 100)) . " Ratus" . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return " Seribu" . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return $this->terbilang(floor($angka / 1000)) . " Ribu" . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return $this->terbilang(floor($angka / 1000000)) . " Juta" . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            return $this->terbilang(floor($angka / 1000000000)) . " Miliar" . $this->terbilang($angka % 1000000000);
        } else {
            return "Angka terlalu besar!";
        }
    }
}