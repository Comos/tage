<?php
/**
 * User: bigbigant
 * Date: Mar 11 2015
 * 
 * This file is only for the examples and tests.
 * In a production environment, to use a global bootstrap to register autoloader is recommended. 
 */

require_once __DIR__.'/src/Tage/Autoloader.php';
\Tage\Autoloader::register();
require_once __DIR__ . '/tests/Tage/Tests/TageTestCase.php';