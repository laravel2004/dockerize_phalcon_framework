<?php

namespace Erp_rmi\Modules\Frontend\Models;

class ActivityLog extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $time_of_work;

    /**
     *
     * @var double
     */
    public $cost;

    /**
     *
     * @var double
     */
    public $total_cost;

    /**
     *
     * @var integer
     */
    public $total_worker;

    /**
     *
     * @var integer
     */
    public $plot_id;

    /**
     *
     * @var string
     */
    public $image;

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
     *
     * @var string
     */
    public $start_date;

    /**
     *
     * @var string
     */
    public $end_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("erp_rmi");
        $this->setSource("activity_log");
        $this->belongsTo('activity_setting_id', 'Erp_rmi\Modules\Frontend\Models\ActivitySetting', 'id', ['alias' => 'activitySetting']);
        $this->belongsTo('plot_id', 'Erp_rmi\Modules\Frontend\Models\\Plot', 'id', ['alias' => 'plot']);
        $this->hasMany(
          'id',
            'Erp_rmi\Modules\Frontend\Models\SupportingMaterial',
            'activity_log_id',
            [
                'alias' => 'supportingMaterials',
                'reusable' => true
            ]
        );
    }

    public function getProject()
    {
        if ($this->plot) {
            return $this->plot->getRelated('project');
        }
        return null;
    }


    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ActivityLog[]|ActivityLog|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ActivityLog|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
