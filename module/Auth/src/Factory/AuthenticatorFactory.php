<?php

namespace Auth\Factory;

use Auth\Authenticator;
use SocialPost\Cache\Factory\CacheFactory;
use SocialPost\Client\Factory\FictionalClientFactory;

/**
 * Class AuthenticatorFactory
 *
 * @package Auth\Factory
 */
class AuthenticatorFactory
{

    /**
     * @return Authenticator
     */
    public static function create(): Authenticator
    {
        try {
            $cache = CacheFactory::create();
        } catch (\Throwable $throwable) {
            // Cache not ready :(
            $cache = null;
        }

        $client = FictionalClientFactory::create();

        return new Authenticator($cache, $client);
    }
}