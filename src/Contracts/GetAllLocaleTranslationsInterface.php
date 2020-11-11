<?php

namespace Stilldesign\Translations\Contracts;

use Illuminate\Support\Collection;

interface GetAllLocaleTranslationsInterface
{
    /**
     * @param $locale
     * @return Collection
     */
    public function get($locale): Collection;
}
