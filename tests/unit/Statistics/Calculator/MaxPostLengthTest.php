<?php

namespace Tests\unit\Statistics\Calculator;

use SocialPost\Hydrator\FictionalPostHydrator;
use Statistics\Calculator\MaxPostLength;
use PHPUnit\Framework\TestCase;
use Statistics\Dto\ParamsTo;

class MaxPostLengthTest extends TestCase
{
    private MaxPostLength $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new MaxPostLength();
        $this->calculator->setParameters((new ParamsTo())->setStatName('max_post_length'));

        $postsData = json_decode(file_get_contents(__DIR__ . '/../../../data/social-posts-response.json'), true);
        $hydrator = new FictionalPostHydrator();
        $posts = array_map([$hydrator, 'hydrate'], $postsData['data']['posts']);

        foreach ($posts as $post) {
            $this->calculator->accumulateData($post);
        }
    }

    public function testCalculate(): void
    {
        $this->assertSame(638.0, $this->calculator->calculate()->getValue());
    }
}
