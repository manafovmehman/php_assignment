<?php

namespace SocialPost\Driver;

use Auth\Authenticator;
use Psr\SimpleCache\InvalidArgumentException;
use SocialPost\Client\SocialClientInterface;
use SocialPost\Exception\BadResponseException;
use SocialPost\Exception\InvalidTokenException;
use Traversable;

/**
 * Class FictionalSocialApiDriver
 *
 * @package SocialPost\Driver
 */
class FictionalDriver implements SocialDriverInterface
{
    private const FETCH_POSTS_URI = '/assignment/posts';

    /**
     * @var SocialClientInterface
     */
    private $client;

    private Authenticator $auth;

    /**
     * FictionalSocialApiDriver constructor.
     *
     * @param SocialClientInterface $client
     * @param Authenticator $auth
     */
    public function __construct(
        SocialClientInterface $client,
        Authenticator $auth
    ) {
        $this->client = $client;
        $this->auth = $auth;
    }

    /**
     * @param int $page
     *
     * @return Traversable
     * @throws InvalidArgumentException
     */
    public function fetchPostsByPage(int $page): Traversable
    {
        $token = $this->auth->getAccessToken();

        try {
            $response = $this->retrievePage($page, $token);
        } catch (InvalidTokenException $exception) {
            // Token was rejected, give it another try with a new one
            $this->auth->invalidateCachedToken();
            $token    = $this->auth->getAccessToken();
            $response = $this->retrievePage($page, $token);
        }

        yield from $this->extractPosts($response);
    }

    /**
     * @param array $responseData
     *
     * @return array
     */
    protected function extractPosts(array $responseData): array
    {
        $posts = $responseData['data']['posts'] ?? null;

        if (null === $posts) {
            throw new BadResponseException('No posts returned');
        }

        return $posts;
    }

    /**
     * @param int    $page
     * @param string $token
     *
     * @return array
     */
    protected function retrievePage(int $page, string $token): array
    {
        $response = $this->client->get(
            self::FETCH_POSTS_URI,
            [
                'page'     => $page,
                'sl_token' => $token,
            ]
        );

        return \GuzzleHttp\json_decode($response, true);
    }
}
