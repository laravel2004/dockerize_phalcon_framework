<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivityLog;
use Erp_rmi\Modules\Frontend\Models\BudgetActivity;
use Erp_rmi\Modules\Frontend\Models\Plot;
use Erp_rmi\Modules\Frontend\Models\Project;
use Erp_rmi\Modules\Frontend\Models\SupportingMaterial;
use Phalcon\Mvc\Controller;

class DashboardController extends Controller
{

    public function indexAction()
    {
        $supportingMaterial = SupportingMaterial::count(["conditions" => "deleted_at IS NULL"]);
        $project = Project::count(["conditions" => "deleted_at IS NULL"]);
        $activityLog = ActivityLog::count(["conditions" => "deleted_at IS NULL"]);
        $project_id = $this->request->getQuery('project_id', 'string', null);

        $conditionActivity = ["deleted_at IS NULL", "period = :period:"];
        $bindActivity = ['period' => intval(date('Y'))];

        if (!empty($project_id)) {
            $conditionActivity[] = "project_id = :project_id:";
            $bindActivity['project_id'] = $project_id;
        }

        $queryOptionActivity = [
            "conditions" => implode(" AND ", $conditionActivity),
            "bind" => $bindActivity
        ];

        $budgetActivity = BudgetActivity::find($queryOptionActivity);
        $activities = [];

        foreach ($budgetActivity as $activity) {
            $conditions = ["deleted_at IS NULL", "activity_setting_id = :activity:", "YEAR(start_date) = :year:"];
            $bind = [
                "activity" => $activity->activity_setting_id,
                "year" => intval(date('Y'))
            ];

            $queryOptions = [
                "conditions" => implode(" AND ", $conditions),
                "bind" => $bind,
                "order" => "created_at DESC"
            ];

            $activitiesLog = ActivityLog::find($queryOptions);

            $actualBudget = 0;
            $actualBudgetActivity = 0;
            $actualBudgetMaterial = 0;

            foreach ($activitiesLog as $log) {
                if (isset($log->plot)) {
                    if (!empty($project_id) && $project_id != $log->plot->project_id) {
                        continue;
                    }

                    $actualBudget += $log->total_cost;
                    $material = 0;

                    if (isset($log->supportingMaterials)) {
                        foreach ($log->supportingMaterials as $sp) {
                            $material += $sp->material->price * $sp->item_needed;
                        }
                    }

                    $actualBudgetMaterial += $material;
                    $actualBudgetActivity += $log->total_cost - $material;
                }
            }

            $activityName = $activity->activitySetting->name ?? 'Unknown Activity';

            if (isset($activities[$activityName])) {
                $activities[$activityName]['budget_cost'] += $activity->budget_labor + $activity->budget_factor;
                $activities[$activityName]['actual_cost'] += $actualBudget;
                $activities[$activityName]['budget_cost_activity'] += $activity->budget_labor;
                $activities[$activityName]['budget_cost_material'] += $activity->budget_factor;
                $activities[$activityName]['actual_cost_activity'] += $actualBudgetActivity;
                $activities[$activityName]['actual_cost_material'] += $actualBudgetMaterial;
            } else {
                $activities[$activityName] = [
                    'id' => $activity->id,
                    'activity_setting_name' => $activityName,
                    'budget_cost' => $activity->budget_labor + $activity->budget_factor,
                    'budget_cost_activity' => $activity->budget_labor,
                    'budget_cost_material' => $activity->budget_factor,
                    'actual_cost' => $actualBudget,
                    'actual_cost_activity' => $actualBudgetActivity,
                    'actual_cost_material' => $actualBudgetMaterial,
                    'status' => 'On Budget'
                ];
            }
        }

        foreach ($activities as &$activity) {
            $activity['status'] = ($activity['actual_cost'] > $activity['budget_cost']) ? 'Over Budget' : 'On Budget';
        }

        $activities = array_values($activities);

        $projects = Project::find([
            'conditions' => 'deleted_at IS NULL',
        ]);

        $this->view->setVars([
            'projects' => $projects,
            'project' => $project,
            'supportingMaterial' => $supportingMaterial,
            'activityLog' => $activityLog,
            'activities' => $activities,
            'project_id' => $project_id,
        ]);

        $this->view->title = 'Dashboard';
        $this->view->subtitle = 'Welcome to Admin Dashboard';
        $this->view->routeName = "dashboard";
    }

    public function detailAction($id)
    {
        $budgetActivity = BudgetActivity::findFirstById($id);
        $plots = Plot::find([
           "conditions" => "deleted_at IS NULL AND project_id = :project_id:",
           "order" => "created_at DESC",
           "bind" => [
               "project_id" => $budgetActivity->project_id
           ]
        ]);

        $total_wide = 0;
        foreach ($plots as $plot) {
            $total_wide += $plot->wide;
        }

        $data = [];
        foreach ($plots as $plot) {

            $activityLogs = ActivityLog::find([
               "conditions" => "deleted_at IS NULL AND plot_id = :plot_id: AND activity_setting_id = :activity_setting_id: AND YEAR(start_date) = :year:",
                "bind" => [
                    "plot_id" => $plot->id,
                    "activity_setting_id" => $budgetActivity->activity_setting_id,
                    "year" => $budgetActivity->period
                ]
            ]);

            $total_cost = 0;
            $actualBudgetActivity = 0;
            $actualBudgetMaterial = 0;
            foreach ($activityLogs as $activityLog) {
                $total_cost += $activityLog->total_cost;
                $material = 0;

                if (isset($activityLog->supportingMaterials)) {
                    foreach ($activityLog->supportingMaterials as $sp) {
                        $material += $sp->material->price * $sp->item_needed;
                    }
                }

                $actualBudgetMaterial += $material;
                $actualBudgetActivity += $activityLog->total_cost - $material;
            }

            $budgetLabor = $budgetActivity->budget_labor * $plot->wide / $total_wide;
            $budgetFactor = $budgetActivity->budget_factor * $plot->wide / $total_wide;
            $data[] = [
                "id" => $plot->id,
                "plot_code" => $plot->code,
                'budget_cost' => $budgetLabor + $budgetFactor,
                'budget_cost_activity' => $budgetLabor,
                'budget_cost_material' => $budgetFactor,
                'actual_cost_activity' => $actualBudgetActivity,
                'actual_cost_material' => $actualBudgetMaterial,
                'actual_cost' => $total_cost,
                "wide" => $plot->wide,
                "status" => $budgetLabor + $budgetFactor > $total_cost ? "On Budget" : "Over Budget"
            ];
        }

        $this->view->title = 'Detail Budget';
        $this->view->subtitle = 'Detail Budget Actual per Plot ' . $budgetActivity->ActivitySetting->name;
        $this->view->routeName = "detail-dashboard";
        $this->view->setVar('activities', $data);
    }

    public function dataAction()
    {
        $this->view->disable();

        $budgetActivities = BudgetActivity::find([
            'conditions' => 'deleted_at IS NULL',
            'columns' => 'DISTINCT period',
            'order' => 'period ASC'
        ]);

        $years = [];
        foreach ($budgetActivities as $activity) {
            $years[] = $activity->period;
        }

        $budgetActivityData = BudgetActivity::find([
            'conditions' => 'deleted_at IS NULL',
            'order' => 'period ASC'
        ]);

        $activityData = [];

        foreach ($budgetActivityData as $activity) {
            $activityName = $activity->activitySetting->name;

            if (!isset($activityData[$activityName])) {
                $activityData[$activityName] = array_fill(0, count($years), 0);
            }

            $activitiesLog = ActivityLog::find([
                'conditions' => 'deleted_at IS NULL AND activity_setting_id = :activity_setting_id: AND YEAR(start_date) = :year:',
                'bind' => [
                    'activity_setting_id' => $activity->activity_setting_id,
                    'year' => $activity->period
                ]
            ]);

            $actualBudget = 0;
            foreach ($activitiesLog as $log) {
                $actualBudget += (int) $log->total_cost;
            }

            $index = array_search($activity->period, $years);
            if ($index !== false) {
                $activityData[$activityName][$index] = $actualBudget;
            }
        }

        $series = [];
        foreach ($activityData as $name => $data) {
            $series[] = [
                'name' => $name,
                'data' => $data
            ];
        }

        return $this->response->setJsonContent([
            'categories' => $years,
            'series' => $series
        ]);
    }

}

