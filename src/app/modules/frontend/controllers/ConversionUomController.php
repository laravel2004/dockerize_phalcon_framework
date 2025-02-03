<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ConversionUom;
use Erp_rmi\Modules\Frontend\Models\UomSetting;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ConversionUomController extends Controller
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
                "model"  => "Erp_rmi\Modules\Frontend\Models\ConversionUom",
                "parameters" => [
                    "conditions" => $conditions,
                    "bind"       => $bindParams,
                ],
                "limit"  => 10,
                "page"   => $numberPage
            ]
        );

        $page = $paginator->paginate();
        $uoms = UomSetting::find([
            'conditions' => 'delete_at IS NULL',
            'order' => 'name ASC'
        ]);

        $page->before = ($page->current > 1) ? $page->current - 1 : null;
        $page->next = ($page->current < ceil($page->total_items / $page->limit)) ? $page->current + 1 : null;
        $page->total_pages = ceil($page->total_items / $page->limit);

        $this->view->setVar('uoms', $uoms);
        $this->view->title = 'Conversion UoM';
        $this->view->subtitle = 'List of conversion UoM';
        $this->view->routeName = "conversion-uom";
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
            $master_uom_start = $this->request->getPost('master_uom_start', 'int');
            $master_uom_end = $this->request->getPost('master_uom_end', 'int');
            $conversion = $this->request->getPost('conversion', 'double');
            $id = $this->request->getPost('id', 'int');

            if ($id) {
                $conversionUom = ConversionUom::findFirstById($id);
            } else {
                $conversionUom = new ConversionUom();
            }

            if (empty($name)) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Name is required'
                ]);
            }

            $conversionUom->name = $name;
            $conversionUom->master_uom_start = $master_uom_start;
            $conversionUom->master_uom_end = $master_uom_end;
            $conversionUom->conversion = $conversion;

            if ($conversionUom->save()) {
                // Response success
                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Conversion UoM berhasil disimpan'
                ]);
            } else {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Failed to save Conversion UoM'
                ]);
            }
        }
    }



    public function deleteAction($id)
    {
        $uomSetting = ConversionUom::findFirstById($id);

        if (!$uomSetting) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Conversion UoM tidak ditemukan'
            ]);
        }

        if ($uomSetting->deleted_at !== null) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Conversion UoM sudah dihapus'
            ]);
        }

        $uomSetting->deleted_at = date('Y-m-d H:i:s');

        if ($uomSetting->save()) {
            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Conversion UoM berhasil dihapus'
            ]);
        } else {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Gagal menghapus Conversion UoM'
            ]);
        }
    }

}