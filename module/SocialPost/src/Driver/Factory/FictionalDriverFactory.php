<?php

namespace SocialPost\Driver\Factory;

use Auth\Factory\AuthenticatorFactory;
use SocialPost\Cache\Factory\CacheFactory;
use SocialPost\Client\Factory\FictionalClientFactory;
use SocialPost\Driver\FictionalDriver;
use SocialPost\Driver\SocialDriverInterface;

/**
 * Class FictionalSocialDriverFactory
 *
 * @package SocialPost\Driver\Factory
 */
class FictionalDriverFactory
{

    /**
     * @return FictionalDriver
     */
    public static function create(): SocialDriverInterface
    {
        $client = FictionalClientFactory::create();
        $auth = AuthenticatorFactory::create();

        return new FictionalDriver($client, $auth);
    }
}
