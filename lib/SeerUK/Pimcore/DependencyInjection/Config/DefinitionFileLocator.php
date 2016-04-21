<?php

namespace SeerUK\Pimcore\DependencyInjection\Config;

/**
 * Container Dist File Locator
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
final class DefinitionFileLocator
{
    /**
     * Get source dist definition file path
     *
     * @return string
     */
    public static function getSourcePath()
    {
        return PIMCORE_PLUGINS_PATH . "/PimcoreDiPlugin/container.dist.php";
    }

    /**
     * Get definition file path
     *
     * @param null|string $environment
     * @return string
     */
    public static function getPath($environment = null)
    {
        $filename = "container";

        if ($environment) {
            $filename .= ".$environment";
        }

        $filename .= ".php";

        return PIMCORE_WEBSITE_PATH . "/config/" . $filename;
    }
}
