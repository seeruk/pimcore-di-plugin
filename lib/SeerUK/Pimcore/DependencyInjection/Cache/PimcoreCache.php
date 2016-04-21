<?php

namespace SeerUK\Pimcore\DependencyInjection\Cache;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ClearableCache;
use Doctrine\Common\Cache\FlushableCache;
use Pimcore\Model\Cache as SystemCache;

/**
 * Doctrine compatible Pimcore cache driver. Uses whichever cache is configured in Pimcore.
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
final class PimcoreCache implements Cache, FlushableCache, ClearableCache
{
    /**
     * {@inheritdoc}
     */
    public function fetch($id)
    {
        $key = $this->transformCacheKey($id);
        $result = SystemCache::load($key);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($id)
    {
        return (bool) $this->fetch($id);
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data, $lifeTime = 0)
    {
        $key = $this->transformCacheKey($id);

        if ($lifeTime === 0) {
            // Pimcore's cache lifetime is infinite if it's null, Doctrine's is if it's 0
            $lifeTime = 999999;
        }

        try {
            SystemCache::save($data, $key, [ "pimcore_doctrine_cache_drive" ], $lifeTime);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        try {
            SystemCache::remove($this->transformCacheKey($id));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStats()
    {
        // Not available, as backend cache driver is unknown
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAll()
    {
        try {
            SystemCache::clearAll();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll()
    {
        try {
            SystemCache::clearAll();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Transforms a cache key so that is will work with Pimcore's Cache
     *
     * @param string $key
     * @return string
     */
    private function transformCacheKey($key)
    {
        return preg_replace("/[^a-zA-Z0-9]+/", "_", $key);
    }
}
