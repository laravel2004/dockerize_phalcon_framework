<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivitySetting;
use Erp_rmi\Modules\Frontend\Models\TypeActivity;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ActivitySettingController extends Controller
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
                "model"  => "Erp_rmi\Modules\Frontend\Models\ActivitySetting",
                "parameters" => [
                    "conditions" => $conditions,
                    "bind"       => $bindParams,
                ],
                "limit"  => 10,
                "page"   => $numberPage
            ]
        );

        $page = $paginator->paginate();
        $typeActivity = TypeActivity::find(["conditions" => "deleted_at IS NULL"]);

        $page->before = ($page->current > 1) ? $page->current - 1 : null;
        $page->next = ($page->current < ceil($page->total_items / $page->limit)) ? $page->current + 1 : null;
        $page->total_pages = ceil($page->total_items / $page->limit);

        $this->view->title = 'Activity Setting';
        $this->view->subtitle = 'List of Activity Setting';
        $this->view->routeName = "activity-setting";
        $this->view->setVar('page', $page);
        $this->view->setVar('search', $search);
        $this->view->setVar('typeActivities', $typeActivity);
    }



    public function createAction()
    {
        //
    }

    public function editAction($id)
    {
        $uomSetting = ActivitySetting::findFirstById($id);
        $this->view->setVar('uomSetting', $uomSetting);
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            $name = $this->request->getPost('name', 'string');
            $description = $this->request->getPost('description', 'string');
            $id = $this->request->getPost('id', 'int');
            $type_activity_id = $this->request->getPost('type_activity_id', 'int');

            try {
                if ($id) {
                    $activitySetting = ActivitySetting::findFirstById($id);
                    if (!$activitySetting) {
                        return $this->response->setJsonContent([
                            'status' => 'error',
                            'message' => 'Activity Setting not found'
                        ]);
                    }
                } else {
                    $activitySetting = new ActivitySetting();
                }

                if (empty($name)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Name is required'
                    ]);
                }

                if (empty($description)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Description is required'
                    ]);
                }

                if (empty($type_activity_id)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Type Activity is required'
                    ]);
                }

                $typeActivity = TypeActivity::find([
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $type_activity_id
                    ]
                ])->getFirst();


                $activitySetting->type_activity_id = $type_activity_id;
                $activitySetting->name = $name;
                $activitySetting->type = $typeActivity->name_type;
                $activitySetting->description = $description;

                if (!$activitySetting->save()) {
                    $errors = [];
                    foreach ($activitySetting->getMessages() as $message) {
                        $errors[] = $message->getMessage();
                    }

                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => $errors
                    ]);
                }

                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'ActivitySetting berhasil disimpan'
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
        $activitySetting = ActivitySetting::findFirstById($id);

        if (!$activitySetting) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => '\ActivitySetting tidak ditemukan'
            ]);
        }

        if ($activitySetting->deleted_at !== null) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => '\ActivitySetting sudah dihapus'
            ]);
        }

        $activitySetting->deleted_at = date('Y-m-d H:i:s');

        if ($activitySetting->save()) {
            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Activity Setting berhasil dihapus'
            ]);
        } else {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Gagal menghapus \ActivitySetting'
            ]);
        }
    }

}