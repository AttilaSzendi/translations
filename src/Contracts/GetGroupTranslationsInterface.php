<?php

namespace Stilldesign\Translations\Contracts;

use Illuminate\Support\Collection;

interface GetGroupTranslationsInterface
{
    /**
     * @param $group
     * @param $locale
     * @return Collection
     */
    public function get(string $group, string $locale): Collection;
}
