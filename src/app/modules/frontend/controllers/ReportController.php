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
        $this->view->title = 'Activity Input';
        $this->view->subtitle = 'Input Activity and Supporting Material';
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
            $worker_needed = $this->request->getPost('worker_needed', 'array', []);
            $is_piece = $this->request->getPost('is_piece') === 'on';

            if (empty($activity_setting_id) || empty($plot_id) || empty($worker_id)) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Required fields are missing'
                ]);
            }

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

            $totalWidth = 0;
            foreach ($plot_id as $p) {
                $plot = Plot::findFirstById($p);
                if ($plot) {
                    $totalWidth += $plot->wide;
                }
            }

            if ($totalWidth == 0) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Total width cannot be zero'
                ]);
            }

            $totalOfWorkData = 0;
            foreach ($worker_needed as $worker) {
                $totalOfWorkData += $worker;
            }

            if ($is_piece) {;
                foreach ($plot_id as $p) {
                    $plot = Plot::findFirstById($p);
                    if (!$plot) {
                        continue;
                    }

                    $timeOfWorkPerPlot = $totalOfWorkData == 0 ? 1 : $totalOfWorkData * ($plot->wide / $totalWidth);
                    $activityLog = new ActivityLog();
                    $activityLog->activity_setting_id = $activity_setting_id;
                    $activityLog->description = $description;
                    $activityLog->time_of_work = $timeOfWorkPerPlot  * ($plot->wide / $totalWidth);
                    $activityLog->cost = $cost->cost * $timeOfWorkPerPlot;
                    $activityLog->total_worker = count($worker_id);
                    $activityLog->total_cost = $cost->cost * $plot->wide;
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

                    foreach ($worker_id as $index => $w) {
                        $worker = WorkerData::findFirstById($w);
                        if (!$worker) {
                            continue;
                        }

                        $payroll = new Payroll();
                        $payroll->worker_data_id = $w;
                        $payroll->activity_log_id = $activityLog->id;
                        $payroll->cost = $cost->cost * $totalWidth ;
                        $payroll->date = $start_date;
                        $payroll->unit = 1 / count($worker_id);
                        $payroll->total_cost = $cost->cost * $plot->wide * 1 / count($worker_id);
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
            }
            else {
                foreach ($plot_id as $p) {
                    $plot = Plot::findFirstById($p);
                    if (!$plot) {
                        continue;
                    }

                    $timeOfWorkPerPlot = $totalOfWorkData == 0 ? 1 : $totalOfWorkData * ($plot->wide / $totalWidth);
                    $activityLog = new ActivityLog();
                    $activityLog->activity_setting_id = $activity_setting_id;
                    $activityLog->description = $description;
                    $activityLog->time_of_work = $timeOfWorkPerPlot;
                    $activityLog->cost = $cost->cost * $timeOfWorkPerPlot;
                    $activityLog->total_worker = count($worker_id);
                    $activityLog->total_cost = $cost->cost * $timeOfWorkPerPlot;
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

                    foreach ($worker_id as $index => $w) {
                        $worker = WorkerData::findFirstById($w);
                        if (!$worker) {
                            continue;
                        }

                        $payroll = new Payroll();
                        $payroll->worker_data_id = $w;
                        $payroll->activity_log_id = $activityLog->id;
                        $payroll->cost = $cost->cost;
                        $payroll->date = $start_date;
                        $payroll->unit = $timeOfWorkPerPlot * $worker_needed[$index];
                        $payroll->total_cost = $cost->cost * $timeOfWorkPerPlot * $worker_needed[$index];
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

    public function deleteAction($id)
    {
        try {
            $activityLog = ActivityLog::findFirstById($id);

            if (!$activityLog) {
                return $this->response
                    ->setStatusCode(404, "Not Found")
                    ->setJsonContent([
                        'status' => 'error',
                        'message' => 'Activity Log tidak ditemukan'
                    ]);
            }

            if (!$activityLog->delete()) {
                return $this->response
                    ->setStatusCode(500, "Internal Server Error")
                    ->setJsonContent([
                        'status' => 'error',
                        'message' => 'Gagal menghapus Activity Log'
                    ]);
            }

            return $this->response
                ->setStatusCode(200, "OK")
                ->setJsonContent([
                    'status' => 'success',
                    'message' => 'Activity Log berhasil dihapus'
                ]);

        } catch (\Exception $error) {
            return $this->response
                ->setStatusCode(500, "Internal Server Error")
                ->setJsonContent([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menghapus Activity Log',
                    'error' => $error->getMessage()
                ]);
        }
    }

    public function historyAction()
    {
        $this->view->title = 'History Activity';
        $this->view->subtitle = 'List of Report History Activity';
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

        $dateRange = $this->request->getQuery("date_range", "string", null);
        if (!empty($dateRange)) {
            $dateRange = explode(" - ", $dateRange);
            $dateStart = date("Y-m-d", strtotime($dateRange[0]));
            $dateEnd = date("Y-m-d", strtotime($dateRange[1]));
            $conditions[] = "start_date BETWEEN :date_start: AND :date_end:";
            $bind["date_start"] = $dateStart;
            $bind["date_end"] = $dateEnd;
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

        $conditionsLog = ["deleted_at IS NULL"];
        $bindLog = [];
        $dateRange = $this->request->getQuery("date_range", "string", null);

        if (!empty($dateRange)) {
            $dateRange = explode(" - ", $dateRange);
            $dateStart = date("Y-m-d", strtotime($dateRange[0]));
            $dateEnd = date("Y-m-d", strtotime($dateRange[1]));
            $conditionsLog[] = "start_date BETWEEN :date_start: AND :date_end:";
            $bindLog["date_start"] = $dateStart;
            $bindLog["date_end"] = $dateEnd;
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
                "conditions" => "plot_id = :plot_id: AND " . implode(" AND ", $conditionsLog),
                "bind" => array_merge($bindLog, [
                    "plot_id" => $plot['id']
                ])
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
        $this->view->setVar('dateRange', $dateRange);
    }

    public function generateAction()
    {
        $this->view->title = 'Report Generate';
        $this->view->subtitle = 'Reporting HO and Finance';
        $this->view->routeName = "report-generate";

        $plot_id = $this->request->getQuery('plot_id', 'string', null);
        $project_id = $this->request->getQuery('project_id', 'string', null);
        $dateRange = $this->request->getQuery("date_range", "string", null);

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

        $conditionsLog = ["deleted_at IS NULL"];
        $bindLog = [];

        if (!empty($dateRange)) {
            $dateRange = explode(" - ", $dateRange);
            $dateStart = date("Y-m-d", strtotime($dateRange[0]));
            $dateEnd = date("Y-m-d", strtotime($dateRange[1]));
            $conditionsLog[] = "start_date BETWEEN :date_start: AND :date_end:";
            $bindLog["date_start"] = $dateStart;
            $bindLog["date_end"] = $dateEnd;
        }

        $queryOptions = [
            "conditions" => implode(" AND ", $conditions),
            "bind" => $bind,
            "order" => "created_at DESC",
        ];

        $plots = Plot::find($queryOptions);
        $plotsArray = $plots->toArray();

        $plotActivityCounts = [];
        $projectActivityCounts = [];

        foreach ($plotsArray as $plot) {
            $activityLogs = ActivityLog::find([
                "conditions" => "plot_id = :plot_id: AND " . implode(" AND ", $conditionsLog),
                "bind" => array_merge($bindLog, ["plot_id" => $plot['id']])
            ]);

            $count = count($activityLogs);
            if ($count > 0) {
                $plotActivityCounts[$plot['id']] = $count;

                $projectCode = Project::findFirstById($plot['project_id'])->code;
                if (!isset($projectActivityCounts[$projectCode])) {
                    $projectActivityCounts[$projectCode] = 1;
                }
                $projectActivityCounts[$projectCode] += $count;
            }
        }

        $total_cost = 0;
        $data = [];
        $last_plot_id = null;
        $last_project_code = null;

        foreach ($plotsArray as $index => $plot) {
            $activityLogs = ActivityLog::find([
                "conditions" => "plot_id = :plot_id: AND " . implode(" AND ", $conditionsLog),
                "bind" => array_merge($bindLog, ["plot_id" => $plot['id']])
            ]);

            $projectCode = Project::findFirstById($plot['project_id'])->code;
            $activity = [];

            $isNewPlot = $plot['id'] !== $last_plot_id;
            $isNewProject = $projectCode !== $last_project_code;

            $row_plot = $isNewPlot ? ($plotActivityCounts[$plot['id']] ?? 1) : 0;
            $row_project = $isNewProject ? ($projectActivityCounts[$projectCode] ?? 1) : 0;

            foreach ($activityLogs as $actIndex => $activityLog) {
                $price = 0;
                foreach ($activityLog->supportingMaterials as $supportingMaterial) {
                    $price += $supportingMaterial->material->price * $supportingMaterial->item_needed;
                }

                $activity[] = [
                    'name' => $activityLog->activitySetting->name,
                    'start_date' => $activityLog->start_date,
                    'end_date' => $activityLog->end_date,
                    'cost' => $activityLog->activitySetting->typeActivity->cost,
                    'plot' => $plot['code'],
                    'project_code' => $projectCode,
                    'unit' => $activityLog->time_of_work,
                    'image' => $activityLog->image,
                    'uom' => 'Hari',
                    'worker' => $activityLog->total_worker,
                    'date' => $activityLog->start_date,
                    'total' => $activityLog->total_cost - $price,
                    'row_plot' => ($actIndex == 0) ? $row_plot : 0,
                    'row_project' => ($actIndex == 0 && $row_plot > 0) ? $row_project : 0,
                ];
            }

            foreach ($activityLogs as $activityLog) {
                $price = 0;
                foreach ($activityLog->supportingMaterials as $supportingMaterial) {
                    $price += $supportingMaterial->material->price * $supportingMaterial->item_needed;
                }
                $total_cost += $activityLog->total_cost - $price;
            }

            $total_cost_activity = array_sum(array_column($activity, 'total'));

            if (!empty($activity)) {
                $data[] = [
                    'plot' => $plot,
                    'activityLogs' => [
                        'activity' => $activity,
                        'total' => $total_cost_activity
                    ]
                ];
            }

            $last_plot_id = $plot['id'];
            $last_project_code = $projectCode;
        }

        if (!isset($projectActivityCounts[$last_project_code])) {
            $row_project = $projectActivityCounts[$last_project_code] ?? 1;
            $data[] = [
                'plot' => ['code' => '-'],
                'activityLogs' => [
                    'activity' => [],
                    'total' => 0,
                    'row_project' => $row_project
                ]
            ];
        }

        $terbilangTotal = trim($this->terbilang($total_cost));

        $this->view->setVar('data', $data);
        $this->view->setVar('terbilangTotal', $terbilangTotal);
        $this->view->setVar('totalCost', $total_cost);
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

    public function searchProjectAction($id)
    {
        $this->view->disable();
        $plots = Plot::find([
           "conditions" => "project_id = :project_id:" ,
            "bind" => [
                "project_id" => $id
            ]
        ]);

        return $this->response->setJsonContent([
            'plots' => $plots
        ]);
    }

}
