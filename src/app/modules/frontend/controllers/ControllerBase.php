<?php
declare(strict_types=1);

namespace Erp_rmi\Modules\Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
  public function initialize()
  {
      $this->view->setTemplateAfter('layouts/main');
  }
}
