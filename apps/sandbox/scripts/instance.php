<?php
/**
 * App instance
 */
namespace Sandbox;

require_once dirname(dirname(dirname(__DIR__))) . '/src/BEAR/Sunday/src.php';
require_once dirname(__DIR__) . '/App.php';

$app = App::factory(App::RUN_MODE_PROD, true);
return $app;
