<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivitySetting;
use Erp_rmi\Modules\Frontend\Models\Project;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class BudgetActivityController extends Controller
{
    public function indexAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $search = $this->request->getQuery('search', 'string', '');

        try {
            $builder = $this->modelsManager->createBuilder()
                ->columns([
                    "ba.id",
                    "ba.activity_setting_id",
                    "ba.project_id",
                    "ba.nominal",
                    "ba.period",
                    "aset.name AS activity_name",
                    "p.project AS project_name",
                ])
                ->from(['ba' => 'Erp_rmi\Modules\Frontend\Models\BudgetActivity'])
                ->join('Erp_rmi\Modules\Frontend\Models\ActivitySetting', 'aset.id = ba.activity_setting_id', 'aset')
                ->join('Erp_rmi\Modules\Frontend\Models\Project', 'p.id = ba.project_id', 'p')
                ->where('ba.deleted_at IS NULL');

            if (!empty($search)) {
                $builder->andWhere('aset.name LIKE :search:', ['search' => '%' . $search . '%'])
                    ->orWhere('p.project LIKE :search:', ['search' => '%' . $search . '%']);
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

            $projects = Project::find(['conditions' => 'deleted_at IS NULL']);
            $activitySettings = ActivitySetting::find(['conditions' => 'deleted_at IS NULL']);

            $this->view->title = 'Budget Activity';
            $this->view->subtitle = 'List of Budget Activity';
            $this->view->routeName = 'budget-activity';
            $this->view->setVar('page', $page);
            $this->view->setVar('search', $search);
            $this->view->setVar('projects', $projects);
            $this->view->setVar('activitySettings', $activitySettings);
        }
        catch (\Exception $e) {
            $this->response->setStatusCode(500, 'Internal Server Error');
            $this->response->setJsonContent(['message' => 'An error occurred while retrieving the data', 'error' => $e->getMessage()]);
            return $this->response->send();
        }
    }

    public function saveAction()
    {
        try{
            if ($this->request->isPost()) {
                $data = $this->request->getPost();

                $budgetActivity = new \Erp_rmi\Modules\Frontend\Models\BudgetActivity();
                $budgetActivity->activity_setting_id = $data['activity_setting_id'];
                $budgetActivity->project_id = $data['project_id'];
                $budgetActivity->nominal = $data['nominal'];
                $budgetActivity->period = $data['period'];

                if ($budgetActivity->save()) {
                    $this->response->setJsonContent(['message' => 'Data saved successfully']);
                    return $this->response->send();
                }
                else {
                    $this->response->setStatusCode(500, 'Internal Server Error');
                    $this->response->setJsonContent(['message' => 'An error occurred while saving the data', 'error' => $budgetActivity->getMessages()]);
                    return $this->response->send();
                }
            }
            else {
                $this->response->setStatusCode(400, 'Bad Request');
                $this->response->setJsonContent(['message' => 'Invalid request method']);
                return $this->response->send();
            }
        }
        catch (\Exception $e) {
            $this->response->setStatusCode(500, 'Internal Server Error');
            $this->response->setJsonContent(['message' => 'An error occurred while saving the data', 'error' => $e->getMessage()]);
            return $this->response->send();
        }
    }

    public function deleteAction($id)
    {
        try {
            $budgetActivity = \Erp_rmi\Modules\Frontend\Models\BudgetActivity::findFirstById($id);
            if ($budgetActivity) {
                $budgetActivity->deleted_at = date('Y-m-d H:i:s');
                if ($budgetActivity->save()) {
                    $this->response->setJsonContent(['message' => 'Data deleted successfully']);
                    return $this->response->send();
                }
                else {
                    $this->response->setStatusCode(500, 'Internal Server Error');
                    $this->response->setJsonContent(['message' => 'An error occurred while deleting the data', 'error' => $budgetActivity->getMessages()]);
                    return $this->response->send();
                }
            }
            else {
                $this->response->setStatusCode(404, 'Not Found');
                $this->response->setJsonContent(['message' => 'Data not found']);
                return $this->response->send();
            }
        }
        catch (\Exception $e) {
            $this->response->setStatusCode(500, 'Internal Server Error');
            $this->response->setJsonContent(['message' => 'An error occurred while deleting the data', 'error' => $e->getMessage()]);
            return $this->response->send();
        }
    }
}
