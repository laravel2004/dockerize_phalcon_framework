<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\Project;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ProjectController extends Controller
{
    public function indexAction()
    {
        $numberPage = $this->request->getQuery("page", "int", 1);
        $search = $this->request->getQuery("search", "string", "");

        $conditions = "deleted_at IS NULL";
        $bindParams = [];

        if (!empty($search)) {
            $conditions .= " AND project LIKE :search:";
            $bindParams["search"] = "%" . $search . "%";
        }

        $paginator = new PaginatorModel(
            [
                "model"  => "Erp_rmi\Modules\Frontend\Models\Project",
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

        $this->view->title = 'Manage Project';
        $this->view->subtitle = 'List of Project';
        $this->view->routeName = "project";
        $this->view->setVar('page', $page);
        $this->view->setVar('search', $search);
    }



    public function createAction()
    {
        //
    }

    public function editAction($id)
    {
        $uomSetting = Project::findFirstById($id);
        $this->view->setVar('uomSetting', $uomSetting);
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            $name = $this->request->getPost('project', 'string');
            $code = $this->request->getPost('code', 'string');
            $id = $this->request->getPost('id', 'int');

            try {
                if ($id) {
                    $project = Project::findFirstById($id);
                    if (!$project) {
                        return $this->response->setJsonContent([
                            'status' => 'error',
                            'message' => 'Project not found'
                        ]);
                    }
                } else {
                    $project = new Project();
                }

                if (empty($name)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Name is required'
                    ]);
                }

                if (empty($code)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Code is required'
                    ]);
                }

                $project->project = $name;
                $project->code = $code;

                if (!$project->save()) {
                    $errors = [];
                    foreach ($project->getMessages() as $message) {
                        $errors[] = $message->getMessage();
                    }

                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => $errors
                    ]);
                }

                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Project berhasil disimpan'
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
        $project = Project::findFirstById($id);
        
        if (!$project) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Project tidak ditemukan'
            ]);
        }

        if ($project->deleted_at !== null) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Project sudah dihapus'
            ]);
        }

        $project->deleted_at = date('Y-m-d H:i:s');

        if ($project->save()) {
            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Project berhasil dihapus'
            ]);
        } else {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Gagal menghapus Project'
            ]);
        }
    }

}