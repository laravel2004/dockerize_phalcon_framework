<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ConversionUom;
use Erp_rmi\Modules\Frontend\Models\Material;
use Erp_rmi\Modules\Frontend\Models\UomSetting;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class MaterialController extends Controller
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
                "model"  => "Erp_rmi\Modules\Frontend\Models\Material",
                "parameters" => [
                    "conditions" => $conditions,
                    "bind"       => $bindParams,
                ],
                "limit"  => 10,
                "page"   => $numberPage
            ]
        );

        $uoms = UomSetting::find(["conditions" => "delete_at IS NULL"]);
        $conversionUoms = ConversionUom::find(["conditions" => "deleted_at IS NULL"]);

        $page = $paginator->paginate();

        $page->before = ($page->current > 1) ? $page->current - 1 : null;
        $page->next = ($page->current < ceil($page->total_items / $page->limit)) ? $page->current + 1 : null;
        $page->total_pages = ceil($page->total_items / $page->limit);

        $this->view->title = 'Manage Material';
        $this->view->subtitle = 'List of Material';
        $this->view->routeName = "material";
        $this->view->setVar('page', $page);
        $this->view->setVar('search', $search);
        $this->view->setVar('uoms', $uoms);
        $this->view->setVar('conversionUoms', $conversionUoms);
    }



    public function createAction()
    {
        //
    }

    public function editAction($id)
    {
        $uomSetting = Material::findFirstById($id);
        $uomSetting->stock = $uomSetting->stock / $uomSetting->conversion_uom->conversion;
        $uomSetting->price = $uomSetting->price * $uomSetting->conversion_uom->conversion;
        $this->view->setVar('uomSetting', $uomSetting);
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            $name = $this->request->getPost('name', 'string');
            $stock = $this->request->getPost('stock', 'string');
//            $uom = $this->request->getPost('uom', 'string');
            $conversion_uom_id = $this->request->getPost('conversion_uom_id', 'int');
            $price = $this->request->getPost('price', 'double');
            $id = $this->request->getPost('id', 'int');

            try {
                if ($id) {
                    $material = Material::findFirstById($id);
                    if (!$material) {
                        return $this->response->setJsonContent([
                            'status' => 'error',
                            'message' => 'Material not found'
                        ]);
                    }
                } else {
                    $material = new Material();
                }

                if (empty($name)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Name is required'
                    ]);
                }

                if (empty($stock)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Stock is required'
                    ]);
                }

                if (empty($conversion_uom_id)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'UOM is required'
                    ]);
                }

                if (empty($price)) {
                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => 'Price is required'
                    ]);
                }

                $uomSetting = ConversionUom::findFirstById($conversion_uom_id);


                $material->name = $name;
                $material->stock = $stock *  $uomSetting->conversion; ;
                $material->uom = $uomSetting->name;
                $material->conversion_uom_id = $conversion_uom_id;
                $material->price = $price / $uomSetting->conversion;

                if (!$material->save()) {
                    $errors = [];
                    foreach ($material->getMessages() as $message) {
                        $errors[] = $message->getMessage();
                    }

                    return $this->response->setJsonContent([
                        'status' => 'error',
                        'message' => $errors
                    ]);
                }

                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Material berhasil disimpan'
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
        $material = Material::findFirstById($id);

        if (!$material) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Material tidak ditemukan'
            ]);
        }

        if ($material->deleted_at !== null) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Material sudah dihapus'
            ]);
        }

        $material->deleted_at = date('Y-m-d H:i:s');

        if ($material->save()) {
            return $this->response->setJsonContent([
                'status' => 'success',
                'message' => 'Material berhasil dihapus'
            ]);
        } else {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Gagal menghapus Material'
            ]);
        }
    }

}