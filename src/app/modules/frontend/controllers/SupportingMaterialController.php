<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivityLog;
use Erp_rmi\Modules\Frontend\Models\ConversionUom;
use Erp_rmi\Modules\Frontend\Models\Material;
use Erp_rmi\Modules\Frontend\Models\Plot;
use Erp_rmi\Modules\Frontend\Models\SupportingMaterial;
use Erp_rmi\Modules\Frontend\Models\UomSetting;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Db\Adapter\Pdo\Exception as DbException;

class SupportingMaterialController extends Controller
{
    public function indexAction()
    {
        $numberPage = $this->request->getQuery("page", "int", 1);
        $search = $this->request->getQuery("search", "string", "");

        try {
            $builder = $this->modelsManager->createBuilder()
                ->columns([
                    'sm.id',
                    'sm.item_needed',
                    'sm.conversion_of_uom_item',
                    'sm.date',
                    'sm.uom',
                    'sm.image',
                    'm.name',
                    'p.code',
                    'activitySetting.name AS activity_name',  // Ubah cara mengakses menjadi activitySetting.name
                ])
                ->from(['sm' => 'Erp_rmi\Modules\Frontend\Models\SupportingMaterial'])
                ->join('Erp_rmi\Modules\Frontend\Models\Material', 'sm.material_id = m.id', 'm')
                ->join('Erp_rmi\Modules\Frontend\Models\Plot', 'sm.plot_id = p.id', 'p')
                ->join('Erp_rmi\Modules\Frontend\Models\ActivityLog', 'sm.activity_log_id = al.id', 'al')
                ->join('Erp_rmi\Modules\Frontend\Models\ActivitySetting', 'al.activity_setting_id = activitySetting.id', 'activitySetting')
                ->where('sm.deleted_at IS NULL');

            if (!empty($search)) {
                $builder->andWhere(
                    'm.name LIKE :search: OR p.code LIKE :search: OR sm.item_needed LIKE :search:',
                    ['search' => '%' . $search . '%']
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

            $materials = Material::find(["conditions" => "deleted_at IS NULL"]);
            $plots = Plot::find(["conditions" => "deleted_at IS NULL"]);
            $uoms = ConversionUom::find(["conditions" => "deleted_at IS NULL"]);
            $activityLogs = ActivityLog::find(["conditions" => "deleted_at IS NULL"]);

            $this->view->title = 'Manage Supporting Material';
            $this->view->subtitle = 'List of Supporting Material';
            $this->view->routeName = "supporting-material";
            $this->view->setVar('page', $page);
            $this->view->setVar('search', $search);
            $this->view->setVar('materials', $materials);
            $this->view->setVar('plots', $plots);
            $this->view->setVar('uoms', $uoms);
            $this->view->setVar('activityLogs', $activityLogs);
        } catch (\Exception $e) {
            $this->response->setStatusCode(500, 'Internal Server Error');
            $this->response->setJsonContent(['message' => 'An error occurred while retrieving the data', 'error' => $e->getMessage()]);
            return $this->response->send();
        }
    }

    public function activityLogsAction()
    {
        $plotId = $this->request->getQuery('plot_id', 'int');

        if (!$plotId) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'Plot ID is required'
            ]);
        }

        $activityLogs = $this->modelsManager->createBuilder()
            ->columns([
                'ActivityLog.id AS log_id',
                'ActivityLog.start_date',
                'ActivityLog.end_date',
                'ActivityLog.description AS log_description',
                'ActivitySetting.name AS activity_name',
                'ActivitySetting.description AS activity_description',
            ])
            ->from(['ActivityLog' => '\Erp_rmi\Modules\Frontend\Models\ActivityLog'])
            ->join('\Erp_rmi\Modules\Frontend\Models\ActivitySetting', 'ActivitySetting.id = ActivityLog.activity_setting_id', 'ActivitySetting') // Join ke tabel ActivitySetting
            ->where('ActivityLog.plot_id = :plot_id:', ['plot_id' => $plotId])
            ->getQuery()
            ->execute();

        if (count($activityLogs) == 0) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'No activity logs found for this plot'
            ]);
        }

        $this->response->setJsonContent([
            'activityLogs' => $activityLogs->toArray()
        ]);

        return $this->response;
    }

    public function saveAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id', 'int');
            $material_id = $this->request->getPost('material_id', 'int');
            $plot_id = $this->request->getPost('plot_id', 'int');
            $item_needed = $this->request->getPost('item_needed', 'string');
            $uom = $this->request->getPost('uom', 'int');
            $date = $this->request->getPost('date', 'string');
            $activity_log_id = $this->request->getPost('activity_log_id', 'int');
            $conversionUom = ConversionUom::findFirstById($uom);

            $material = Material::findFirstById($material_id);
            $stockMaterial = $material->stock * $conversionUom->conversion;

            if ($stockMaterial < $item_needed) {
                $this->response->setStatusCode(400, 'Bad Request');
                $this->response->setJsonContent(['message' => 'Material stock is not enough']);
                return $this->response->send();
            }

            $transaction = $stockMaterial - $item_needed;

            $this->db->begin();

            try {
                $material->stock = $transaction / $conversionUom->conversion;
                $material->save();

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

                $supportingMaterial = $id ? SupportingMaterial::findFirstById($id) : new SupportingMaterial();
                $supportingMaterial->material_id = $material_id;
                $supportingMaterial->plot_id = $plot_id;
                $supportingMaterial->item_needed = $item_needed;
                $supportingMaterial->conversion_of_uom_item = $conversionUom->conversion;
                $supportingMaterial->uom = $conversionUom->uom_end->name;
                $supportingMaterial->image = json_encode($imagePaths) ?? [];
                $supportingMaterial->date = $date;
                $supportingMaterial->activity_log_id = $activity_log_id;

                if (!$supportingMaterial->save()) {

                    $messages = $supportingMaterial->getMessages();
                    $errors = [];
                    foreach ($messages as $message) {
                        $errors[] = $message->getMessage();
                    }

                    throw new \Exception(implode(', ', $errors));
                }

                // Commit transaction
                $this->db->commit();

                $this->response->setJsonContent(['message' => 'Data saved successfully']);
                return $this->response->send();
            } catch (\Exception $e) {
                // Rollback transaction
                $this->db->rollback();

                $this->response->setStatusCode(400, 'Bad Request');
                $this->response->setJsonContent(['message' => 'Failed to save data', 'error' => $e->getMessage()]);
                return $this->response->send();
            }
        }
    }

    public function deleteAction($id)
    {
        try {
            $project = SupportingMaterial::findFirstById($id);

            if (!$project) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Supporting Material tidak ditemukan'
                ]);
            }

            if ($project->deleted_at !== null) {
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Supporting Material sudah dihapus'
                ]);
            }

            $material = Material::findFirstById($project->material_id);
            $stockMaterial = $material->stock * $project->conversion_of_uom_item;

            $transaction = $stockMaterial + $project->item_needed;
            $material->stock = $transaction / $project->conversion_of_uom_item;

            // Start DB transaction
            $this->db->begin();

            $project->deleted_at = date('Y-m-d H:i:s');

            if ($project->save() && $material->save()) {
                // Commit transaction
                $this->db->commit();
                return $this->response->setJsonContent([
                    'status' => 'success',
                    'message' => 'Supporting Material berhasil dihapus'
                ]);
            } else {
                // Rollback transaction
                $this->db->rollback();
                return $this->response->setJsonContent([
                    'status' => 'error',
                    'message' => 'Gagal menghapus SupportingMaterial'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJsonContent([
                'status' => 'error',
                'message' => 'An error occurred while deleting the Supporting Material',
                'error' => $e->getMessage()
            ]);
        }
    }
}