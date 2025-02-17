<?php

namespace Erp_rmi\Modules\Frontend\Models;

class Material extends \Phalcon\Mvc\Model
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
    public $conversion_uom_id;

    /**
     *
     * @var double
     */
    public $price;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var double
     */
    public $stock;

    /**
     *
     * @var string
     */
    public $uom;

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
        $this->setSource("material");
        $this->belongsTo('conversion_uom_id', '\Erp_rmi\Modules\Frontend\Models\ConversionUom', 'id', ['alias' => 'conversion_uom']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Material[]|Material|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Material|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
