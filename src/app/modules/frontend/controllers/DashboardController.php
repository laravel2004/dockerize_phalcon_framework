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
        $supportingMaterial = SupportingMaterial::find(["conditions" => "deleted_at IS NULL"])->count();
        $project = Project::find(["conditions" => "deleted_at IS NULL"])->count();
        $activityLog = ActivityLog::find(["conditions" => "deleted_at IS NULL"])->count();
        $project_id = $this->request->getQuery('project_id', 'string', null);

        $conditionActivity = [
            "deleted_at IS NULL"
        ];

        $bindActivity = [];

        if (!empty($project_id)) {
            $conditionActivity[] = "project_id = :project_id:";
            $bindActivity['project_id'] = $project_id;
        }

        $conditionActivity[] = "period = :period:";
        $bindActivity['period'] = intval(date('Y'));

        $queryOptionActivity = [];

        if (!empty($conditionActivity)) {
            $queryOptionActivity["conditions"] = implode(" AND ", $conditionActivity);
            $queryOptionActivity["bind"] = $bindActivity;
        }

        $budgetActivity = BudgetActivity::find($queryOptionActivity);

        $activities = [];

        foreach ($budgetActivity as $activity) {
            $conditions = [
                "deleted_at IS NULL"
            ];
            $bind = [];

            $conditions[] = "activity_setting_id = :activity:";
            $bind["activity"] = $activity->activity_setting_id;

            $conditions[] = "YEAR(start_date) = :year:";
            $bind["year"] = intval(date('Y'));

            $queryOptions = [
                "order" => "created_at DESC"
            ];

            if (!empty($conditions)) {
                $queryOptions["conditions"] = implode(" AND ", $conditions);
                $queryOptions["bind"] = $bind;
            }

            $activitiesLog = ActivityLog::find($queryOptions);

            $actualBudget = 0;

            foreach ($activitiesLog as $log) {
                if (!empty($project_id)) {
                    if ($project_id == $log->plot->project_id) {
                        $actualBudget += $log->total_cost;
                    }
                } else {
                    $actualBudget += $log->total_cost;
                }
            }

            $activityName = $activity->activitySetting->name;

            if (isset($activities[$activityName])) {
                $activities[$activityName]['budget_cost'] += $activity->nominal;
            } else {
                $activities[$activityName] = [
                    'id' => $activity->id,
                    'activity_setting_name' => $activityName,
                    'budget_cost' => $activity->nominal,
                    'actual_cost' => $actualBudget,
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

        $this->view->setVar('projects', $projects);
        $this->view->title = 'Dashboard';
        $this->view->subtitle = 'Welcome to Admin Dashboard';
        $this->view->routeName = "dashboard";
        $this->view->setVar('project', $project);
        $this->view->setVar('supportingMaterial', $supportingMaterial);
        $this->view->setVar('activityLog', $activityLog);
        $this->view->setVar('activities', $activities);
        $this->view->setVar('project_id', $project_id);

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
            foreach ($activityLogs as $activityLog) {
                $total_cost += $activityLog->total_cost;
            }

            $budget = $budgetActivity->nominal * $plot->wide / $total_wide;
            $data[] = [
                "id" => $plot->id,
                "plot_code" => $plot->code,
                'budget_cost' => $budget,
                'actual_cost' => $total_cost,
                "wide" => $plot->wide,
                "status" => $budget > $total_cost ? "On Budget" : "Over Budget"
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

