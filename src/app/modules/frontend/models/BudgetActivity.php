<?php

namespace Erp_rmi\Modules\Frontend\Models;

class BudgetActivity extends \Phalcon\Mvc\Model
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
    public $activity_setting_id;

    /**
     *
     * @var integer
     */
    public $project_id;

    /**
     *
     * @var double
     */
    public $nominal;

    /**
     *
     * @var string
     */
    public $period;

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
     *
     * @var string
     */
    public $deleted_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("erp_rmi");
        $this->setSource("budget_activity");
        $this->belongsTo('activity_setting_id', 'Erp_rmi\Modules\Frontend\Models\ActivitySetting', 'id', ['alias' => 'ActivitySetting']);
        $this->belongsTo('project_id', 'Erp_rmi\Modules\Frontend\Models\Project', 'id', ['alias' => 'Project']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BudgetActivity[]|BudgetActivity|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BudgetActivity|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }


}
