<?php

namespace Erp_rmi\Modules\Frontend\Models;

class UomSetting extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $create_at;

    /**
     *
     * @var string
     */
    public $update_at;

    /**
     *
     * @var string
     */
    public $delete_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("erp_rmi");
        $this->setSource("uom_setting");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UomSetting[]|UomSetting|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UomSetting|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
