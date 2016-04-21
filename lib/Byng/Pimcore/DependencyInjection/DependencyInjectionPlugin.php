<?php

namespace Byng\Pimcore\DependencyInjection;

use Byng\Pimcore\DependencyInjection\Cache\PimcoreCache;
use Byng\Pimcore\DependencyInjection\Config\DefinitionFileLocator;
use DI\Bridge\ZendFramework1\Dispatcher;
use DI\Cache\ArrayCache;
use DI\ContainerBuilder;
use Pimcore\API\Plugin\AbstractPlugin;
use Pimcore\API\Plugin\PluginInterface;
use Pimcore\Config;

/**
 * Dependency Injection Plugin
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class DependencyInjectionPlugin extends AbstractPlugin implements PluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $environment = Config::getSystemConfig()->get("general")->get("environment");

        // Set up dependency injection container
        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);

        // Configure the container
        $builder->addDefinitions(PIMCORE_WEBSITE_PATH . "/config/container.php");

        if ($environment !== "local") {
            // Use whatever cache Pimcore has configured (if it has one configured) to cache object
            // definitions in the container
            $builder->setDefinitionCache(new PimcoreCache());
        } else {
            // For development, or debugging we don't want to cache the container
            $builder->setDefinitionCache(new ArrayCache());
        }

        $environmentConfigFile = DefinitionFileLocator::getPath($environment);

        if (file_exists($environmentConfigFile)) {
            $builder->addDefinitions($environmentConfigFile);
        }

        $container = $builder->build();

        $dispatcher = new Dispatcher();
        $dispatcher->setContainer($container);

        \Zend_Controller_Front::getInstance()->setDispatcher($dispatcher);
    }

    /**
     * {@inheritdoc}
     */
    public static function install()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function uninstall()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function isInstalled()
    {
        return true;
    }
}
