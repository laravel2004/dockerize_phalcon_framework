<?php

namespace Erp_rmi\Modules\Frontend\Models;

class Plot extends \Phalcon\Mvc\Model
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
    public $code;

    /**
     *
     * @var integer
     */
    public $project_id;

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
        $this->setSource("Plot");
        $this->belongsTo(
            "project_id",
            "Erp_rmi\Modules\Frontend\Models\Project",
            "id",
            [
                'alias' => 'project'
            ]
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Plot[]|Plot|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Plot|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
