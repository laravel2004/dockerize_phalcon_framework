<?php

namespace Erp_rmi\Modules\Frontend\Models;

class ConversionUom extends \Phalcon\Mvc\Model
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
    public $master_uom_start;

    /**
     *
     * @var integer
     */
    public $master_uom_end;

    /**
     *
     * @var double
     */
    public $conversion;

    /**
     *
     * @var string
     */
    public $name;

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
        $this->setSource("conversion_uom");
        $this->belongsTo('master_uom_start', '\Erp_rmi\Modules\Frontend\Models\UomSetting', 'id', ['alias' => 'uom_start']);
        $this->belongsTo('master_uom_end', '\Erp_rmi\Modules\Frontend\Models\UomSetting', 'id', ['alias' => 'uom_end']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConversionUom[]|ConversionUom|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConversionUom|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
