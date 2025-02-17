<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Erp_rmi\Modules\Frontend\Models\WorkerData;

class WorkerDataController extends Controller
{
    public function indexAction()
    {
        $numberPage = $this->request->getQuery("page", "int", 1);
        $search = $this->request->getQuery("search", "string", "");

        $conditions = "deleted_at IS NULL";
        $bindParams = [];

        if (!empty($search)) {
            $conditions .= " AND name LIKE :search:";
            $bindParams["search"] = "%" . $search . "%";
        }

        $paginator = new PaginatorModel(
            [
                "model"  => "Erp_rmi\Modules\Frontend\Models\WorkerData",
                "parameters" => [
                    "conditions" => $conditions,
                    "bind"       => $bindParams,
                ],
                "limit"  => 10,
                "page"   => $numberPage
            ]
        );

        $page = $paginator->paginate();

        $page->before = ($page->current > 1) ? $page->current - 1 : null;
        $page->next = ($page->current < ceil($page->total_items / $page->limit)) ? $page->current + 1 : null;
        $page->total_pages = ceil($page->total_items / $page->limit);

        $this->view->title = 'Worker Data';
        $this->view->subtitle = 'List of Worker Data';
        $this->view->routeName = "worker-data";
        $this->view->setVar('page', $page);
        $this->view->setVar('search', $search);
    }

    public function createAction()
    {
        //
    }

    public function editAction($id)
    {
        $workerData = WorkerData::findFirstById($id);
        $this->view->setVar('workerData', $workerData);
    }

    public function saveAction()
    {
        try {
            if ($this->request->isPost()) {
                $id = $this->request->getPost('id', 'int');
                $name = $this->request->getPost('name', 'string');
                $address = $this->request->getPost('address', 'string');
                $no_rekening = $this->request->getPost('no_rekening', 'string');
                $nama_bank = $this->request->getPost('nama_bank', 'string');

                if ($id) {
                    $workerData = WorkerData::findFirstById($id);
                } else {
                    $workerData = new WorkerData();
                }

                $imagePaths = [];

                foreach ($this->request->getUploadedFiles() as $imageFile) {
                    if ($imageFile->isUploadedFile()) {
                        $uploadDir = 'uploads' . DIRECTORY_SEPARATOR . 'worker_data';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        $filePath = $uploadDir . DIRECTORY_SEPARATOR . time() . '_' . $imageFile->getName();
                        $imageFile->moveTo($filePath);

                        $imagePaths[] = $filePath;
                    }
                }

                $workerData->name = $name;
                $workerData->address = $address;
                $workerData->no_rekening = $no_rekening;
                $workerData->nama_bank = $nama_bank;
                $workerData->image = json_encode($imagePaths) ?? [];

                if ($workerData->save()) {
                    return $this->response->setJsonContent([
                        'status' => 'success',
                        'message' => 'Data saved successfully'
                    ]);
                } else {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Failed to save data'
                    ]);
                }
            }

        }
        catch (\Exception $e) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteAction($id)
    {
        try {
            $workerData = WorkerData::findFirstById($id);
            if (!$workerData) {
                throw new \Exception('Worker Data not found');
            }

            $workerData->deleted_at = date('Y-m-d H:i:s');

            if ($workerData->save()) {
                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Worker Data deleted successfully'
                ]);
            } else {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Failed to delete Worker Data'
                ]);
            }
        }
        catch (\Exception $e) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}