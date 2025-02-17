<?php

namespace Erp_rmi\Modules\Frontend\Models;

class SupportingMaterial extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $item_needed;

    /**
     *
     * @var double
     */
    public $conversion_of_uom_item;

    /**
     *
     * @var string
     */
    public $uom;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var integer
     */
    public $plot_id;

    /**
     *
     * @var integer
     */
    public $material_id;

    /**
     *
     * @var integer
     */
    public $activity_log_id;

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
    public $date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("erp_rmi");
        $this->setSource("supporting_material");
        $this->belongsTo(
            "material_id",
            "Erp_rmi\Modules\Frontend\Models\Material",
            "id",
            [
                'alias' => 'material'
            ]
        );
        $this->belongsTo(
            "plot_id",
            "Erp_rmi\Modules\Frontend\Models\Plot",
            "id",
            [
                'alias' => 'plot'
            ]
        );
        $this->belongsTo(
            "activity_log_id",
            "Erp_rmi\Modules\Frontend\Models\ActivityLog",
            "id",
            [
                'alias' => 'activityLog'
            ]
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SupportingMaterial[]|SupportingMaterial|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SupportingMaterial|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
