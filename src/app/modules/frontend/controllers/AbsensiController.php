<?php

namespace Erp_rmi\Modules\Frontend\Controllers;

use Erp_rmi\Modules\Frontend\Models\ActivityLog;
use Erp_rmi\Modules\Frontend\Models\Payroll;
use Phalcon\Mvc\Controller;

class AbsensiController extends Controller
{
    public function indexAction()
    {
        $dateRange = $this->request->getQuery("date_range", "string", null);
        $conditions = [];
        $bind = [];

        if (!empty($dateRange)) {
            $dateRange = explode(" - ", $dateRange);
            $dateStart = date("Y-m-d", strtotime($dateRange[0]));
            $dateEnd = date("Y-m-d", strtotime($dateRange[1]));
            $conditions[] = "date BETWEEN :date_start: AND :date_end:";
            $bind["date_start"] = $dateStart;
            $bind["date_end"] = $dateEnd;
        }

        $queryOptions = [];
        if (!empty($conditions)) {
            $queryOptions["conditions"] = implode(" AND ", $conditions);
            $queryOptions["bind"] = $bind;
        }

        $payrolls = Payroll::find($queryOptions);
        $response = [];
        $dates = [];

        $start = new \DateTime($dateStart);
        $end = new \DateTime($dateEnd);
        while ($start <= $end) {
            $dates[] = $start->format("Y-m-d");
            $start->modify("+1 day");
        }

        $attendanceData = [];

        foreach ($payrolls as $payroll) {
            $activityLog = ActivityLog::findFirstById($payroll->activity_log_id);

            if (!$activityLog) continue;

            $workerName = $payroll->WorkerData->name;

            if (!isset($attendanceData[$workerName])) {
                $attendanceData[$workerName] = array_fill_keys($dates, "0");
            }

            foreach ($dates as $date) {
                if ($date >= $activityLog->start_date && $date <= $activityLog->end_date) {
                    $attendanceData[$workerName][$date] = "1";
                }
            }
        }

        $data = [];
        foreach ($attendanceData as $name => $attendance) {
            $data[] = [
                "nama" => $name,
                "attendance" => $attendance
            ];
        }

        $this->view->title = 'Absensi Worker';
        $this->view->subtitle = 'List of Absensi Worker';
        $this->view->routeName = "absensi-worker";
        $this->view->setVar('dates', $dates);
        $this->view->setVar('data', $data);
        $this->view->setVar('dateRange', $dateRange);
    }
}
