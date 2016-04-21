<?php

namespace Byng\Pimcore\DependencyInjection\Config;

/**
 * Container Dist File Locator
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
final class DefinitionFileLocator
{
    /**
     * Get destination path
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
