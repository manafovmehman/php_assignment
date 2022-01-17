<?php

namespace Auth;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use SocialPost\Client\SocialClientInterface;
use SocialPost\Exception\BadResponseException;

class Authenticator
{
    private const REGISTER_TOKEN_URI = '/assignment/register';

    private const TOKEN_CACHE_KEY = 'fictional-access-token';

    private ?CacheInterface $cache;

    private SocialClientInterface $client;

    public function __construct(?CacheInterface $cache, SocialClientInterface $client)
    {
        $this->cache = $cache;
        $this->client = $client;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getAccessToken(): string
    {
        $token = null;
        if (null !== $this->cache) {
            $token = $this->cache->get(self::TOKEN_CACHE_KEY);
        }

        if (null === $token) {
            $token = $this->registerToken();
        }

        return $token;
    }

    public function getUser(): User
    {
        return new User();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function invalidateCachedToken(): void
    {
        if (null === $this->cache) {
            return;
        }

        $this->cache->delete(self::TOKEN_CACHE_KEY);
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function registerToken(): string
    {
        $userData = $this->getUser()->jsonSerialize();

        $response = $this->client->authRequest(self::REGISTER_TOKEN_URI, $userData);
        $response = \GuzzleHttp\json_decode($response, true);

        $token = $response['data']['sl_token'] ?? null;
        if (null === $token) {
            throw new BadResponseException('No access token returned');
        }

        if (null !== $this->cache) {
            $this->cache->set(self::TOKEN_CACHE_KEY, $token, (int)$_ENV['TOKEN_CACHE_TTL']);
        }

        return $token;
    }
}