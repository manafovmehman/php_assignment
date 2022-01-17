<?php

namespace SocialPost\Cache\Factory;

use Memcached;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * Class CacheFactory
 *
 * @package SocialPost\Cache\Factory
 */
class CacheFactory
{

    /**
     * @throws \Exception
     * @return CacheInterface
     */
    public static function create(): CacheInterface
    {
        return new Psr16Cache(new MemcachedAdapter(self::getClient()));
    }

    /**
     * @return Memcached
     */
    protected static function getClient(): Memcached
    {
        $memcached = new Memcached();
        $memcached->addServer($_ENV['MEMCACHED_HOST'], $_ENV['MEMCACHED_PORT']);

        return $memcached;
    }
}
