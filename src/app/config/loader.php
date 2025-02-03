<?php

use Phalcon\Autoload\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->setNamespaces([
    'Erp_rmi\Models' => APP_PATH . '/common/models/',
    'Erp_rmi'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->setClasses([
    'Erp_rmi\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Erp_rmi\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
