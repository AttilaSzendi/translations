<?php

namespace Stilldesign\Translations\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Stilldesign\Translations\Models\Translation;

interface TranslationRepositoryInterface
{
    /**
     * @param string $group
     * @param string $locale
     * @return Collection
     */
    public function findAllByGroupAndLocale(string $group, string $locale): Collection;

    /**
     * @param string $group
     * @param string $locale
     * @param string $item
     * @return \Illuminate\Database\Eloquent\Model|Builder|Translation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByGroupLocaleAndItem(string $group, string $locale, string $item): Translation;

    /**
     * @return Collection
     */
    public function findAllGroup(): Collection;

    /**
     * @param string $keyword
     * @return Collection
     */
    public function findAllByKeyword(string $keyword): Collection;

    /**
     * @param string $locale
     * @return Collection
     */
    public function findAllByLocale(string $locale): Collection;

    /**
     * @param Translation $model
     * @return Translation
     */
    public function save(Translation $model): Translation;

    /**
     * @param string $group
     * @return Collection
     */
    public function findAllKeyFieldByGroup(string $group): Collection;
}
