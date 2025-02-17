<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\TypeActivity;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class TypeActivityController extends Controller
{

        public function indexAction()
        {
            $numberPage = $this->request->getQuery("page", "int", 1);
            $search = $this->request->getQuery("search", "string", "");

            $conditions = "deleted_at IS NULL";
            $bindParams = [];

            if (!empty($search)) {
                $conditions .= " AND name_type LIKE :search:";
                $bindParams["search"] = "%" . $search . "%";
            }

            $paginator = new PaginatorModel(
                [
                    "model"  => "Erp_rmi\Modules\Frontend\Models\TypeActivity",
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

            $this->view->title = 'Manage Type Activity';
            $this->view->subtitle = 'List of Type Activity';
            $this->view->routeName = "type-activity";
            $this->view->setVar('page', $page);
            $this->view->setVar('search', $search);
        }

        public function createAction()
        {
            $this->view->title = 'Create Type Activity';
            $this->view->subtitle = 'Create Type Activity';
            $this->view->routeName = "type-activity";
        }

        public function saveAction()
        {
            try {
                $name = $this->request->getPost('name_type');
                $cost = str_replace([',', '.'], '', $this->request->getPost('cost'));
                $id = $this->request->getPost('id');

                if ($id) {
                    $typeActivity = TypeActivity::findFirstById($id);
                    if (!$typeActivity) {
                        throw new \Exception('Type Activity not found');
                    }
                } else {
                    $typeActivity = new TypeActivity();
                }

                if (empty($name)) {
                    throw new \Exception('Name Type Activity is required');
                }

                if (empty($cost)) {
                    throw new \Exception('Cost is required');
                }

                $typeActivity->name_type = $name;
                $typeActivity->cost = $cost;

                if (!$typeActivity->save()) {
                    throw new \Exception('Failed to save Type Activity');
                }

                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Type Activity saved successfully'
                ]);
            }
            catch (\Exception $e) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        public function editAction($id)
        {
            $typeActivity = TypeActivity::findFirstById($id);
            $this->view->setVar('typeActivity', $typeActivity);
        }

        public function deleteAction($id)
        {
            $typeActivity = TypeActivity::findFirstById($id);
            if (!$typeActivity) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Type Activity not found'
                ]);
            }

            $typeActivity->deleted_at = date('Y-m-d H:i:s');
            if (!$typeActivity->save()) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Failed to delete Type Activity'
                ]);
            }

            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Type Activity deleted successfully'
            ]);
        }
}
