<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class NoopCalculator extends AbstractCalculator
{
    protected const UNITS = 'posts';

    private int $postCount = 0;

    private array $users = [];

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $this->postCount++;
        $this->users[$postTo->getAuthorId()] = 1;
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $value = $this->postCount > 0
            ? $this->postCount / count($this->users)
            : 0;

        return (new StatisticsTo())->setValue(round($value));
    }
}
