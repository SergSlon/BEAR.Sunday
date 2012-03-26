<?php
/**
 * Module
 *
 * @package    BEAR.Framework
 * @subpackage Module
 */
namespace BEAR\Framework\Module\Extention;

use Ray\Di\Scope;

use Ray\Di\AbstractModule,
    Ray\Di\Injector;
use Ray\Aop\Interceptor;


/**
 * View module
 *
 * @package    BEAR.Framework
 * @subpackage Module
 */
class ViewModule extends AbstractModule
{
    /**
     * Constructor
     *
     * @param Interceptor[] $interceptors
     */
    public function __construct(array $htmlInterceptors)
    {
        $this->htmlInterceptors = $htmlInterceptors;
        parent::__construct();
    }

    /**
     * Configure
     *
     * @return void
     */
    protected function configure()
    {
        $this->bindInterceptor(
                $this->matcher->annotatedWith('BEAR\Framework\Annotation\Html'),
                $this->matcher->any(),
                $this->htmlInterceptors
        );
    }
}