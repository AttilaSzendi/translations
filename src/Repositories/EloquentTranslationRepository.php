<?php

namespace Stilldesign\Translations\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Stilldesign\Translations\Contracts\TranslationRepositoryInterface;
use Stilldesign\Translations\Models\Translation;

class EloquentTranslationRepository implements TranslationRepositoryInterface
{
    protected $model;

    /**
     * EloquentTranslationRepository constructor.
     * @param Translation $model
     */
    public function __construct(Translation $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $group
     * @param string $locale
     * @return Collection
     */
    public function findAllByGroupAndLocale(string $group, string $locale): Collection
    {
        return $this->model->newQuery()
            ->where('group', $group)
            ->where('locale', $locale)
            ->get();
    }

    /**
     * @param string $group
     * @param string $locale
     * @param string $item
     * @return \Illuminate\Database\Eloquent\Model|Builder|Translation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByGroupLocaleAndItem(string $group, string $locale, string $item): Translation
    {
        return $this->model->newQuery()
            ->where('group', $group)
            ->where('locale', $locale)
            ->where('item', $item)
            ->firstOrFail();
    }


    /**
     * @return Collection
     */
    public function findAllGroup(): Collection
    {
        return $this->model->newQuery()->select(['group'])->distinct()->get();
    }

    /**
     * @param string $keyword
     * @return Collection
     */
    public function findAllByKeyword(string $keyword): Collection
    {
        return $this->model->newQuery()
            ->where('text', 'like', '%'. $keyword .'%')
            ->get();
    }

    /**
     * @param string $locale
     * @return Collection
     */
    public function findAllByLocale(string $locale): Collection
    {
        return $this->model->newQuery()
            ->where('locale', $locale)
            ->get();
    }

    /**
     * @param Translation $model
     * @return Translation
     */
    public function save(Translation $model): Translation
    {
        $model->save();
        return $model;
    }

    /**
     * @param string $group
     * @return Collection
     */
    public function findAllKeyFieldByGroup(string $group): Collection
    {
        return $this->model->newQuery()
            ->select(['namespace', 'group', 'item'])
            ->where('group', $group)
            ->distinct()->get();
    }
}
