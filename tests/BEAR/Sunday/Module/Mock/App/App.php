<?php
/**
 * mockapp
 *
 * @package BEAR.appmock
 */
namespace BEAR\Sunday\Module\App;

use BEAR\Sunday\Extension\Application\AppInterface;

/**
 * Application
 *
 * @package App.appmock
 */
final class App implements AppInterface
{
    public $name = __NAMESPACE__;
    public $path = __DIR__;
}
