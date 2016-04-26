<?php

namespace SeerUK\Pimcore\DependencyInjection;

use SeerUK\Pimcore\DependencyInjection\Cache\PimcoreCache;
use SeerUK\Pimcore\DependencyInjection\Config\DefinitionFileLocator;
use DI\Bridge\ZendFramework1\Dispatcher;
use DI\Cache\ArrayCache;
use DI\ContainerBuilder;
use Pimcore\API\Plugin\AbstractPlugin;
use Pimcore\API\Plugin\PluginInterface;
use Pimcore\Config;
use SeerUK\Pimcore\DependencyInjection\Config\ParametersFileLocator;

/**
 * Dependency Injection Plugin
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
final class DependencyInjectionPlugin extends AbstractPlugin implements PluginInterface
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
        $parametersFile = ParametersFileLocator::getPath();

        if (file_exists($environmentConfigFile)) {
            $builder->addDefinitions($environmentConfigFile);
        }

        if (file_exists($parametersFile)) {
            $builder->addDefinitions($parametersFile);
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
        $definitionFile = DefinitionFileLocator::getPath();
        $sourceFile = DefinitionFileLocator::getSourcePath();

        if (!file_exists($definitionFile)) {
            copy($sourceFile, $definitionFile);
        }

        return "Dependency injection plugin installed successfully.";
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
        return file_exists(DefinitionFileLocator::getPath());
    }
}
