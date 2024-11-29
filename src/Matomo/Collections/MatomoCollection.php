<?php

namespace RedSnapper\MatomoCharts\Matomo\Collections;

use Illuminate\Support\Collection;

class MatomoCollection extends Collection
{
    public function filterSpamRequests(): MatomoCollection
    {
        return $this->filter(fn($item) => !str($item->label)->startsWith('/?'));
    }

    public function filterShortKeywords(): MatomoCollection
    {
        return $this->filter(fn($item) => str($item->label)->length() > 2);
    }

    public function getFirstAndReturnMatomoCollection(): MatomoCollection
    {
        return new MatomoCollection($this->first());
    }
}
