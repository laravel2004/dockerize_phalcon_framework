<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\Plot;
use Erp_rmi\Modules\Frontend\Models\Project;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class PlotController extends Controller
{
    public function indexAction()
    {
        $numberPage = $this->request->getQuery("page", "int", 1);
        $search = $this->request->getQuery("search", "string", "");

        $conditions = "deleted_at IS NULL";
        $bindParams = [];

        if (!empty($search)) {
            $conditions .= " AND code LIKE :search:";
            $bindParams["search"] = "%" . $search . "%";
        }

        $paginator = new PaginatorModel(
            [
                "model"  => "Erp_rmi\Modules\Frontend\Models\Plot",
                "parameters" => [
                    "conditions" => $conditions,
                    "bind"       => $bindParams,
                ],
                "limit"  => 10,
                "page"   => $numberPage
            ]
        );

        $projects = Project::find(["conditions" => "deleted_at IS NULL"]);

        $page = $paginator->paginate();

        $page->before = ($page->current > 1) ? $page->current - 1 : null;
        $page->next = ($page->current < ceil($page->total_items / $page->limit)) ? $page->current + 1 : null;
        $page->total_pages = ceil($page->total_items / $page->limit);

        $this->view->title = 'Plot';
        $this->view->subtitle = 'List of Plot';
        $this->view->routeName = "plot";
        $this->view->setVar('page', $page);
        $this->view->setVar('search', $search);
        $this->view->setVar('projects', $projects);
    }



    public function createAction()
    {
        //
    }

    public function editAction($id)
    {
        $uomSetting = \Plot()::findFirstById($id);
        $this->view->setVar('uomSetting', $uomSetting);
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            $code = $this->request->getPost('code', 'string');
            $projectId = $this->request->getPost('project_id', 'string');
            $id = $this->request->getPost('id', 'int');
            $wide = $this->request->getPost('wide', 'double');

            try {
                if ($id) {
                    $plot = Plot::findFirstById($id);
                    if (!$plot) {
                        return $this->response->setJsonContent([
                            'status' => 'error',
                            'message' => 'Plot not found'
                        ]);
                    }
                } else {
                    $plot = new Plot();
                }

                if (empty($projectId)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Project is required'
                    ]);
                }

                if (empty($code)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Code is required'
                    ]);
                }

                $project = Project::find([
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $projectId
                    ]
                ])->getFirst();
                if (!$project) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Project not found'
                    ]);
                }

                $project->wide += $wide - $plot->wide;
                $plot->code = $code;
                $plot->project_id = $projectId;
                $plot->wide = $wide;

                if (!$project->save()) {
                    $errors = [];
                    foreach ($plot->getMessages() as $message) {
                        $errors[] = $message->getMessage();
                    }

                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => $errors
                    ]);
                }


                if (!$plot->save()) {
                    $errors = [];
                    foreach ($plot->getMessages() as $message) {
                        $errors[] = $message->getMessage();
                    }

                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => $errors
                    ]);
                }

                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Plot berhasil disimpan'
                ]);
            } catch (\Exception $e) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        return $this->response->setJsonContent([
            'status' => 'error',
            'message' => 'Invalid request method'
        ]);
    }

    public function deleteAction($id)
    {
        $plot = Plot::findFirstById($id);

        if (!$plot) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Plot tidak ditemukan'
            ]);
        }

        if ($plot->deleted_at !== null) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Plot sudah dihapus'
            ]);
        }

        $plot->deleted_at = date('Y-m-d H:i:s');

        if ($plot->save()) {
            $project = Project::findFirstById($plot->project_id);
            $project->wide = $project->wide - $plot->wide;

            if (!$project->save()) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Gagal menghapus Plot'
                ]);
            }
            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Plot berhasil dihapus'
            ]);
        } else {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Gagal menghapus UoM Setting'
            ]);
        }
    }

}