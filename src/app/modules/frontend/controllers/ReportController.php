<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivityLog;
use Erp_rmi\Modules\Frontend\Models\ActivitySetting;
use Erp_rmi\Modules\Frontend\Models\BudgetActivity;
use Erp_rmi\Modules\Frontend\Models\Material;
use Erp_rmi\Modules\Frontend\Models\Payroll;
use Erp_rmi\Modules\Frontend\Models\Plot;
use Erp_rmi\Modules\Frontend\Models\Project;
use Erp_rmi\Modules\Frontend\Models\SupportingMaterial;
use Erp_rmi\Modules\Frontend\Models\TypeActivity;
use Erp_rmi\Modules\Frontend\Models\WorkerData;
use Phalcon\Mvc\Controller;

class ReportController extends Controller
{
    public function indexAction()
    {
        $this->view->title = 'Report';
        $this->view->subtitle = 'List of Report';
        $this->view->routeName = "report";

        $this->view->setVars([
            'projects' => Project::find(["conditions" => "deleted_at IS NULL"]),
            'activitySettings' => ActivitySetting::find(["conditions" => "deleted_at IS NULL"]),
            'plots' => Plot::find(["conditions" => "deleted_at IS NULL"]),
            'workers' => WorkerData::find(["conditions" => "deleted_at IS NULL"]),
            'materials' => Material::find(["conditions" => "deleted_at IS NULL"]),
        ]);
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }

        try {
            // Ambil input dari request
            $is_include = $this->request->getPost('is_include') === 'on';
            $activity_setting_id = $this->request->getPost('activity_setting_id', 'int');
            $start_date = $this->request->getPost('start_date', 'string');
            $end_date = $this->request->getPost('end_date', 'string');
            $plot_id = $this->request->getPost('plot_id', 'array', []);
            $description = $this->request->getPost('description', 'string', '');
            $worker_id = $this->request->getPost('worker_id', 'array', []);
            $supporting_material_id = $this->request->getPost('supporting_material_id', 'array', []);
            $item_needed = $this->request->getPost('item_needed', 'array', []);

            if (empty($activity_setting_id) || empty($plot_id) || empty($worker_id)) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Required fields are missing'
                ]);
            }

            // Konversi tanggal
            $startDateTime = strtotime($start_date);
            $endDateTime = strtotime($end_date);
            if (!$startDateTime || !$endDateTime) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Invalid date format'
                ]);
            }

            $timeOfWork = (($endDateTime - $startDateTime) / (60 * 60 * 24)) + 1;

            $this->db->begin();

            // Proses upload file
            $imagePaths = [];
            foreach ($this->request->getUploadedFiles() as $imageFile) {
                if ($imageFile->isUploadedFile()) {
                    $uploadDir = 'uploads' . DIRECTORY_SEPARATOR . 'activity_logs';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $filePath = $uploadDir . DIRECTORY_SEPARATOR . time() . '_' . $imageFile->getName();
                    $imageFile->moveTo($filePath);
                    $imagePaths[] = $filePath;
                }
            }

            // Validasi ActivitySetting dan TypeActivity
            $activitySetting = ActivitySetting::findFirstById($activity_setting_id);
            if (!$activitySetting) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Activity setting not found'
                ]);
            }

            $cost = TypeActivity::findFirstById($activitySetting->type_activity_id);
            if (!$cost) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Activity type not found'
                ]);
            }

            // Hitung total width
            $totalWidth = 0;
            foreach ($plot_id as $p) {
                $plot = Plot::findFirstById($p);
                if ($plot) {
                    $totalWidth += $plot->wide;
                }
            }

            // Cek agar tidak terjadi pembagian dengan nol
            if ($totalWidth == 0) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Total width cannot be zero'
                ]);
            }

            foreach ($plot_id as $p) {
                $plot = Plot::findFirstById($p);
                if (!$plot) {
                    continue;
                }

                $timeOfWorkPerPlot = $timeOfWork * ($plot->wide / $totalWidth);
                $activityLog = new ActivityLog();
                $activityLog->activity_setting_id = $activity_setting_id;
                $activityLog->description = $description;
                $activityLog->time_of_work = $timeOfWorkPerPlot;
                $activityLog->cost = $cost->cost * $timeOfWorkPerPlot;
                $activityLog->total_worker = count($worker_id);
                $activityLog->total_cost = $cost->cost * $timeOfWorkPerPlot * count($worker_id);
                $activityLog->plot_id = $p;
                $activityLog->image = json_encode($imagePaths);
                $activityLog->start_date = $start_date;
                $activityLog->end_date = $end_date;

                if (!$activityLog->save()) {
                    $this->db->rollback();
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Failed to save activity log'
                    ]);
                }

                foreach ($worker_id as $w) {
                    $worker = WorkerData::findFirstById($w);
                    if (!$worker) {
                        continue;
                    }

                    $payroll = new Payroll();
                    $payroll->worker_data_id = $w;
                    $payroll->activity_log_id = $activityLog->id;
                    $payroll->cost = $cost->cost;
                    $payroll->date = $start_date;
                    $payroll->unit = $timeOfWorkPerPlot;
                    $payroll->total_cost = $cost->cost * $timeOfWorkPerPlot;
                    $payroll->status = 0;

                    if (!$payroll->save()) {
                        $this->db->rollback();
                        return $this->response->setJsonContent([
                            'status' => 'error',
                            'message' => $payroll->getMessages()
                        ]);
                    }
                }

                if (!$payroll->save()) {
                    $this->db->rollback();
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => $payroll->getMessages()
                    ]);
                }

                if ($is_include) {
                    foreach ($supporting_material_id as $index => $sp) {
                        $material = Material::findFirstById($sp);
                        if (!$material) {
                            continue;
                        }

                        if ($material->stock < $item_needed[$index]) {
                            $this->db->rollback();
                            return $this->response->setJsonContent([
                                'status' => 'error',
                                'message' => 'Insufficient stock for ' . $material->name
                            ]);
                        }

                        $needed = ($plot->wide / $totalWidth) * $item_needed[$index] ?? 0;
                        $supportingMaterial = new SupportingMaterial();
                        $supportingMaterial->material_id = $sp;
                        $supportingMaterial->activity_log_id = $activityLog->id;
                        $supportingMaterial->date = $start_date;
                        $supportingMaterial->plot_id = $p;
                        $supportingMaterial->item_needed = $needed;
                        $supportingMaterial->uom = $material->uom;
                        $supportingMaterial->conversion_of_uom_item = $material->conversion_uom->conversion ?? 1;
                        $supportingMaterial->image = json_encode($imagePaths);

                        if (!$supportingMaterial->save()) {
                            $this->db->rollback();
                            return $this->response->setJsonContent([
                                'status' => 'error',
                                'message' => 'Failed to save supporting material'
                            ]);
                        }

                        $activityLog->total_cost += $supportingMaterial->material->price * $needed;

                        if (!$activityLog->save()) {
                            $this->db->rollback();
                            return $this->response->setJsonContent([
                                'status' => 'error',
                                'message' => 'Failed to save activity log'
                            ]);
                        }

                        $material->stock -= $needed;
                        if (!$material->save()) {
                            $this->db->rollback();
                            return $this->response->setJsonContent([
                                'status' => 'error',
                                'message' => 'Failed to update stock'
                            ]);
                        }
                    }
                }
            }

            $this->db->commit();

            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Activity log saved successfully'
            ]);
        } catch (\Exception $e) {
            $this->db->rollback();
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function historyAction()
    {
        $this->view->title = 'Report History';
        $this->view->subtitle = 'List of Report History';
        $this->view->routeName = "report-history";
        $this->request->getQuery('plot', 'string', '');
        $this->request->getQuery('material', 'string', '');
        $this->request->getQuery('project', 'string', '');
        $project = Project::find([
            "conditions" => "deleted_at IS NULL",
            "order" => "created_at DESC"
        ]);

        $year = date('Y');

        $years = [];
        for ($i = 0; $i < 50; $i++) {
            $years[] = $year - $i;
        }

        $conditions = [
            "deleted_at IS NULL"
        ];
        $bind = [];

        $yearDate = $this->request->getQuery('year', 'int', null);
        if (!empty($yearDate)) {
            $conditions[] = "YEAR(start_date) = :year:";
            $bind["year"] = $yearDate;
        }

        $plot_id = $this->request->getQuery('plot_id', 'string', null);
        if (!empty($plot_id)) {
            $conditions[] = "plot_id = :plot_id:";
            $bind["plot_id"] = $plot_id;
        }
        $queryOptions = [
            "order" => "created_at DESC"
        ];

        if (!empty($conditions)) {
            $queryOptions["conditions"] = implode(" AND ", $conditions);
            $queryOptions["bind"] = $bind;
        }

        $activityLogs = ActivityLog::find($queryOptions);

        $project_id = $this->request->getQuery('project_id', 'string', null);
        if (!empty($project_id)) {
            $activityLog = [];
            foreach ($activityLogs as $log) {
                if ($log->plot->project_id == $project_id) {
                    $activityLog[] = $log;
                }
            }
        }
        else {
            $activityLog = $activityLogs;
        }

        $this->view->setVar('activityLogs', $activityLog);
        $this->view->setVar('projects', $project);
        $this->view->setVar('years', $years);
    }

    public function searchPlotAction($id)
    {
        try {
            $plot = Plot::find([
                "conditions" => "project_id = :project_id:",
                "bind" => [
                    "project_id" => $id
                ]
            ]);

            return $this->response->setJsonContent([
                'status' => 'success',
                'data' => $plot
            ]);
        }
        catch (\Exception $e) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detailAction($id)
    {
        $activityLog = ActivityLog::findFirstById($id);
        if (!$activityLog) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Activity log not found'
            ]);
        }

        $data = [];
        $price = 0;
        foreach ($activityLog->supportingMaterials as $supportingMaterial) {
            $data[] = [
                'name' => $supportingMaterial->material->name,
                'unit' => $supportingMaterial->item_needed,
                'uom' => $supportingMaterial->material->conversion_uom->uom_end->name,
                'total' => $supportingMaterial->material->price * $supportingMaterial->item_needed
            ];
            $price += $supportingMaterial->material->price * $supportingMaterial->item_needed;
        }
        $data[] = [
            'name' =>$activityLog->activitySetting->name,
            'unit' => $activityLog->time_of_work,
            'uom' => 'Hari',
            'total' => $activityLog->total_cost - $price
        ];

        $this->view->title = 'Report Detail';
        $this->view->subtitle = 'Detail of Report';
        $this->view->routeName = "report-detail";
        $this->view->setVar('activityLog', $activityLog);
        $this->view->setVar('data', $data);

        $terbilangTotal = trim($this->terbilang($activityLog->total_cost));

        $this->view->setVar('terbilangTotal', $terbilangTotal);
    }

    public function summarizeAction()
    {
        $this->view->title = 'Report Summarize';
        $this->view->subtitle = 'Form of Report Summarize';
        $this->view->routeName = "report-summarize";

        $plot_id = $this->request->getQuery('plot_id', 'string', null);
        $project_id = $this->request->getQuery('project_id', 'string', null);
        $year = $this->request->getQuery('year', 'int', null);

        $conditions = ["deleted_at IS NULL"];
        $bind = [];

        if (!empty($plot_id)) {
            $conditions[] = "id = :id:";
            $bind["id"] = $plot_id;
        }

        if (!empty($project_id)) {
            $conditions[] = "project_id = :project_id:";
            $bind["project_id"] = $project_id;
        }

        $queryOptions = [
            "conditions" => implode(" AND ", $conditions),
            "bind" => $bind,
            "order" => "created_at DESC",
        ];

        $plots = Plot::find($queryOptions);

        $plotsArray = $plots->toArray();

        $total_cost = 0;

        $data = [];

        foreach ($plotsArray as $plot) {
            $activityLogs = ActivityLog::find([
                "conditions" => "plot_id = :plot_id:",
                "bind" => [
                    "plot_id" => $plot['id']
                ]
            ]);

            $activity = [];
            foreach ($activityLogs as $activityLog) {
                $price = 0;
                foreach ($activityLog->supportingMaterials as $supportingMaterial) {
                    $price += $supportingMaterial->material->price * $supportingMaterial->item_needed;
                    $activity[] = [
                        'name' => $supportingMaterial->material->name,
                        'unit' => $supportingMaterial->item_needed,
                        'uom' => $supportingMaterial->material->conversion_uom->uom_end->name,
                        'total' => $supportingMaterial->material->price * $supportingMaterial->item_needed
                    ];
                }
                $activity[] = [
                    'name' => $activityLog->activitySetting->name,
                    'unit' => $activityLog->time_of_work,
                    'uom' => 'Hari',
                    'total' => $activityLog->total_cost - $price
                ];
            }


            foreach ($activityLogs as $activityLog) {
                $total_cost += $activityLog->total_cost;
            }

            $total_cost_activity = 0;

            foreach ($activity as $act) {
                $total_cost_activity += $act['total'];
            }

            $data[] = [
                'plot' => $plot,
                'activityLogs' => [
                    'activity' => $activity,
                    'total' => $total_cost_activity
                ]
            ];
        }

        $terbilangTotal = trim($this->terbilang($total_cost));

        $this->view->setVar('data', $data);
        $this->view->setVar('terbilangTotal', $terbilangTotal);
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
