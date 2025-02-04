<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivityLog;
use Erp_rmi\Modules\Frontend\Models\ActivitySetting;
use Erp_rmi\Modules\Frontend\Models\Plot;
use Erp_rmi\Modules\Frontend\Models\Worker;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ActivityLogController extends Controller
{
    public function indexAction()
    {
        $numberPage = $this->request->getQuery("page", "int", 1);
        $search = $this->request->getQuery("search", "string", "");

        try {
            $builder = $this->modelsManager->createBuilder()
                ->columns([
                    'al.id',
                    'al.description',
                    'al.time_of_work',
                    'al.cost',
                    'al.total_cost',
                    'al.total_worker',
                    'al.plot_id',
                    'al.image',
                    'al.start_date',
                    'al.end_date',
                    'al.time_of_work',
                    'activity_setting.name as activity_setting_name',
                    'activity_setting.type as activity_setting_type',
                    'p.code as plot_code',
                    'activity_setting.type as activity_setting_type'
                ])
                ->from(['al' => 'Erp_rmi\Modules\Frontend\Models\ActivityLog'])
                ->join('Erp_rmi\Modules\Frontend\Models\ActivitySetting', 'al.activity_setting_id = activity_setting.id', 'activity_setting')
                ->join('Erp_rmi\Modules\Frontend\Models\Plot', 'al.plot_id = p.id', 'p')
                ->where('al.deleted_at IS NULL');

            if (!empty($search)) {
                $builder->andWhere(
                    'activity_setting.name LIKE :search1: OR al.description LIKE :search2: OR p.code LIKE :search3:',
                    [
                        'search1' => '%' . $search . '%',
                        'search2' => '%' . $search . '%',
                        'search3' => '%' . $search . '%'
                    ]
                );
            }

            $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder(
                [
                    'builder' => $builder,
                    'limit'   => 10,
                    'page'    => $numberPage,
                ]
            );

            $page = $paginator->paginate();

            $page->before = ($page->current > 1) ? $page->current - 1 : null;
            $page->next = ($page->current < ceil($page->total_items / $page->limit)) ? $page->current + 1 : null;
            $page->total_pages = ceil($page->total_items / $page->limit);

            $this->view->title = 'Activity Log';
            $this->view->subtitle = 'List of Activity Log';
            $this->view->routeName = "activity-log";
            $this->view->setVar('page', $page);
            $this->view->setVar('search', $search);
        }
        catch (\Exception $e) {
            $this->response->setStatusCode(500, 'Internal Server Error');
            $this->response->setJsonContent(['message' => 'An error occurred while retrieving the data', 'error' => $e->getMessage()]);
            return $this->response->send();
        }
    }

    public function createAction()
    {
        $activitySettings = ActivitySetting::find(["conditions" => "deleted_at IS NULL"]);
        $plots = Plot::find(["conditions" => "deleted_at IS NULL"]);

        $this->view->title = 'Create Activity Log';
        $this->view->subtitle = 'Create new Activity Log';
        $this->view->routeName = "activity-log";
        $this->view->setVar('activitySettings', $activitySettings);
        $this->view->setVar('plots', $plots);
    }

    public function editAction($id)
    {
        $activityLog = ActivityLog::findFirst($id);

        if (!$activityLog) {
            $this->response->setStatusCode(404, 'Not Found');
            $this->response->setJsonContent(['message' => 'Activity Log not found.']);
            return $this->response->send();
        }

        $activitySettings = ActivitySetting::find(["conditions" => "deleted_at IS NULL"]);
        $plots = Plot::find(["conditions" => "deleted_at IS NULL"]);
        $workers = Worker::find([
            'conditions' => 'activity_log_id = :id:',
            'bind' => ['id' => $activityLog->id]
        ]);

        $this->view->title = 'Edit Activity Log';
        $this->view->subtitle = 'Edit existing Activity Log';
        $this->view->routeName = "activity-log";
        $this->view->setVar('activityLog', $activityLog);
        $this->view->setVar('activitySettings', $activitySettings);
        $this->view->setVar('plots', $plots);
        $this->view->setVar('workers', $workers);
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            $activityLogId = $this->request->getPost('id', 'int');
            $activitySettingId = $this->request->getPost('activity_setting_id', 'int');
            $description = $this->request->getPost('description', 'string');
            $timeOfWorks = $this->request->getPost('time_of_work', 'int', null);
            $cost = str_replace([',', '.'], '', $this->request->getPost('cost'));
            $cost = (float) $cost;
            $totalWorker = $this->request->getPost('total_worker', 'int');
            $plotId = $this->request->getPost('plot_id', 'int');
            $imageFile = $this->request->getUploadedFiles()[0] ?? null;
            $workers = $this->request->getPost('workers', null, []);
            $startDate = $this->request->getPost('start_date', 'string');
            $endDate = $this->request->getPost('end_date', 'string');

            if (!is_array($workers)) {
                return $this->response
                    ->setStatusCode(500, 'Internal Server Error')
                    ->setJsonContent([
                    'status' => 'error',
                    'message' => 'Invalid workers data format'
                ])->send();
            }

            $activitySetting = ActivitySetting::findFirst([
                'conditions' => 'id = :id:',
                'bind' => ['id' => $activitySettingId]
            ]);
            if ($activitySetting->type == "Jam" && $startDate!= $endDate) {
                return $this->response
                    ->setStatusCode(500, 'Internal Server Error')
                    ->setJsonContent([
                        'status' => 'error',
                        'message' => 'Start date and end date must be the same for hourly activity'
                    ])
                    ->send();
            }

            if ($startDate > $endDate) {
                return $this->response
                    ->setStatusCode(500, 'Internal Server Error')
                    ->setJsonContent([
                        'status' => 'error',
                        'message' => 'Start date must be less than end date'
                    ])
                    ->send();
            }

            try {
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

                        $imagePaths[] = $filePath; // Simpan path ke array
                    }
                }

                $totalCost = $cost * $totalWorker;

                $timeOfWork = null;
                if ($activitySetting->type == "Harian") {
                    $startDateTime = strtotime($startDate);
                    $endDateTime = strtotime($endDate);
                    $timeOfWork = (($endDateTime - $startDateTime) / (60 * 60 * 24)) + 1;
                }

                if ($activitySetting->type == "Jam") {
                    $timeOfWork = $timeOfWorks;
                }

                if ($timeOfWork != null) {
                    $totalCost *= $timeOfWork;
                }

                $activityLog = new ActivityLog();
                $activityLog->activity_setting_id = $activitySettingId;
                $activityLog->description = $description;
                $activityLog->time_of_work = $timeOfWork;
                $activityLog->cost = $cost;
                $activityLog->total_cost = $totalCost;
                $activityLog->total_worker = $totalWorker;
                $activityLog->plot_id = $plotId;
                $activityLog->image = json_encode($imagePaths) ?? [];
                $activityLog->start_date = $startDate;
                $activityLog->end_date = $endDate;

                if (!$activityLog->save()) {
                    throw new \Exception('Failed to save activity log: ' . implode(', ', $activityLog->getMessages()));
                }

                // Simpan Worker
                foreach ($workers as $workerData) {
                    if (empty($workerData['name'])) {
                        throw new \Exception('Worker name cannot be empty');
                    }

                    $worker = new Worker();
                    $worker->name = $workerData['name'];
                    $worker->activity_log_id = $activityLog->id;

                    if (!$worker->save()) {
                        throw new \Exception('Failed to save worker: ' . implode(', ', $worker->getMessages()));
                    }
                }

                // Commit transaksi
                $this->db->commit();

                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Activity log and workers saved successfully.',
                ]);
            } catch (\Exception $e) {
                $this->db->rollback();
                $this->response->setStatusCode(500, 'Internal Server Error');
                $this->response->setJsonContent([
                    'message' => 'An error occurred while saving the data',
                    'error' => $e->getMessage(),
                ]);
                return $this->response->send();
            }
        }

        // Jika bukan POST
        return $this->response->setStatusCode(405, 'Method Not Allowed')->setJsonContent([
            'message' => 'Invalid request method.',
        ]);
    }


    public function deleteAction($id)
    {
        // Periksa apakah ID valid
        if (!isset($id) || empty($id) || !is_numeric($id)) {
            return $this->response->setStatusCode(400, 'Bad Request')->setJsonContent([
                'status' => 'error',
                'message' => 'Invalid ID provided.',
            ]);
        }

        // Lanjutkan proses delete
        try {
            $this->db->begin();

            $activityLog = ActivityLog::findFirst($id);

            if (!$activityLog) {
                return $this->response->setStatusCode(404, 'Not Found')->setJsonContent([
                    'status' => 'error',
                    'message' => 'Activity log not found.',
                ]);
            }

            // Menghapus pekerja terkait
            $workers = Worker::find([
                'conditions' => 'activity_log_id = :id:',
                'bind' => ['id' => $id]
            ]);

            foreach ($workers as $worker) {
                if (!$worker->delete()) {
                    throw new \Exception('Failed to delete worker: ' . implode(', ', $worker->getMessages()));
                }
            }

            if (!empty($activityLog->images)) {
                $imagePaths = json_decode($activityLog->images, true); // Decode JSON ke array

                if (is_array($imagePaths)) {
                    foreach ($imagePaths as $imagePath) {
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                }
            }

            // Hapus Activity Log
            if (!$activityLog->delete()) {
                throw new \Exception('Failed to delete activity log: ' . implode(', ', $activityLog->getMessages()));
            }

            // Commit transaksi
            $this->db->commit();

            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Activity log and related workers deleted successfully.',
            ]);
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->response->setStatusCode(500, 'Internal Server Error');
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ]);
        }
    }

}