<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\UomSetting;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class UomSettingController extends Controller
{
    public function indexAction()
    {
        $numberPage = $this->request->getQuery("page", "int", 1);
        $search = $this->request->getQuery("search", "string", "");

        $conditions = "delete_at IS NULL";
        $bindParams = [];

        if (!empty($search)) {
            $conditions .= " AND name LIKE :search:";
            $bindParams["search"] = "%" . $search . "%";
        }

        $paginator = new PaginatorModel(
            [
                "model"  => "Erp_rmi\Modules\Frontend\Models\UomSetting",
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

        $this->view->title = 'Uom Setting';
        $this->view->subtitle = 'List of Uom Setting';
        $this->view->routeName = "uom-setting";
        $this->view->setVar('page', $page);
        $this->view->setVar('search', $search);
    }



    public function createAction()
    {
        //
    }

    public function editAction($id)
    {
        $uomSetting = UomSetting::findFirstById($id);
        $this->view->setVar('uomSetting', $uomSetting);
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            $name = $this->request->getPost('name', 'string');
            $id = $this->request->getPost('id', 'int');

            if ($id) {
                $uomSetting = UomSetting::findFirstById($id);
            } else {
                $uomSetting = new UomSetting();
            }

            if (empty($name)) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Name is required'
                ]);
            }

            $uomSetting->name = $name;

            if ($uomSetting->save()) {
                // Response success
                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'UoM Setting berhasil disimpan'
                ]);
            } else {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Failed to save UoM Setting'
                ]);
            }
        }
    }



    public function deleteAction($id)
    {
        $uomSetting = UomSetting::findFirstById($id);
        
        if (!$uomSetting) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'UoM Setting tidak ditemukan'
            ]);
        }

        if ($uomSetting->delete_at !== null) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'UoM Setting sudah dihapus'
            ]);
        }

        $uomSetting->delete_at = date('Y-m-d H:i:s');

        if ($uomSetting->save()) {
            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'UoM Setting berhasil dihapus'
            ]);
        } else {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Gagal menghapus UoM Setting'
            ]);
        }
    }

}