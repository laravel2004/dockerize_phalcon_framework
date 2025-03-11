<?php

namespace Erp_rmi\Modules\Frontend\Models;

class Payroll extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $worker_data_id;

    /**
     *
     * @var integer
     */
    public $activity_log_id;

    /**
     *
     * @var double
     */
    public $cost;

    /**
     *
     * @var double
     */
    public $unit;

    /**
     *
     * @var double
     */
    public $total_cost;

    /**
     *
     * @var boolean
     */
    public $status;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("erp_rmi");
        $this->setSource("payroll");
        $this->belongsTo('worker_data_id', 'Erp_rmi\Modules\Frontend\Models\WorkerData', 'id', ['alias' => 'WorkerData']);
        $this->belongsTo('activity_log_id', 'Erp_rmi\Modules\Frontend\Models\ActivityLog', 'id', ['alias' => 'ActivityLog']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Payroll[]|Payroll|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Payroll|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }
}