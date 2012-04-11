<?php
/**
 * Module
 *
 * @package    sandbox
 * @subpackage Module
 */
namespace sandbox\Module;

use BEAR\Framework\Module\TemplateEngine\SmartyModule;

use Ray\Di\Scope;

use BEAR\Framework\Module\StandardModule;
use BEAR\Framework\Module;
use BEAR\Framework\Module\Extension;
use BEAR\Framework\Interceptor\DbInjector;
use BEAR\Framework\Interceptor\ViewAdapter;
use BEAR\Framework\Interceptor\ViewAdapter\SmartyBackend;
use Ray\Di\AbstractModule;
use Ray\Di\InjectorInterface;
use Ray\Di\Annotation;
use Ray\Di\Config;
use Ray\Di\Forge;
use Ray\Di\Container;
use Ray\Di\Injector as Di;
use Ray\Di\Definition;
use Ray\Di\Injector;
use Guzzle\Common\Cache\ZendCacheAdapter as CacheAdapter;
use Zend\Cache\Backend\File as CacheBackEnd;
use Smarty;
use ReflectionClass;

/**
 * Application module
 *
 * @package    sandbox
 * @subpackage Module
 */
class AppModule extends AbstractModule
{
    public $tmpDir;

    private $mode = '';

    public function __construct($mode = 0)
    {
        $this->mode = $mode;
        $this->tmpDir = dirname(__DIR__) . '/tmp';
        parent::__construct();
    }

    /**
     * Configure dependency binding
     *
     * @return void
     */
    protected function configure()
    {
        switch ($this->mode) {
            case 'dev':
            default:
                $this
                ->bind()
                ->annotatedWith('master_db')
                ->toInstance(['driver' => 'pdo_mysql', 'host' => 'localhost', 'dbname' => 'blogbear', 'user' => 'root', 'password' => null, 'charset' => 'UTF8']);

                $this
                ->bind()
                ->annotatedWith('slave_db')
                ->toInstance(['driver' => 'pdo_mysql', 'host' => 'localhost', 'dbname' => 'blogbear', 'user' => 'root', 'password' => null, 'charset' => 'UTF8']);
                ;
                break;

        }
        $this->bindInterceptor(
            $this->matcher->subclassesOf('sandbox\Resource\Page\Index'),
            $this->matcher->any(),
            [new \sandbox\Interceptor\Checker($this->tmpDir)]
        );

        $this->installCommon();
    }

    private function installCommon()
    {
        $this->install(new Module\Database\DoctrineDbalModule);
        $this->install(new Module\Schema\StandardSchemaModule);
        //         $this->install(new Module\Cqrs\CacheModule);
        $this->install(new Module\WebContext\AuraWebModule);
        $this->install(new Module\TemplateEngine\SmartyModule);
    }
}