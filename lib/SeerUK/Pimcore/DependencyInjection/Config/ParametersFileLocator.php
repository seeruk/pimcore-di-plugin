<?php

namespace SeerUK\Pimcore\DependencyInjection\Config;

/**
 * Parameters Dist File Locator
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
final class ParametersFileLocator
{
    /**
     * Get definition file path
     *
     * @return string
     */
    public static function getPath()
    {
        return PIMCORE_WEBSITE_PATH . "/config/parameters.dist.php";
    }
}
