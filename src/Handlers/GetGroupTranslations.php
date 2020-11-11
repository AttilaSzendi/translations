<?php

namespace Stilldesign\Translations\Handlers;

use Illuminate\Database\Eloquent\Collection;
use Stilldesign\Translations\Contracts\GetGroupTranslationsInterface;
use Stilldesign\Translations\Contracts\TranslationRepositoryInterface;
use Stilldesign\Translations\Models\Translation;
use Illuminate\Support\Collection as IlluminateCollection;

class GetGroupTranslations implements GetGroupTranslationsInterface
{
    protected $translationRepository;
    protected $translationsCollection;
    protected $translation;

    /**
     * @param TranslationRepositoryInterface $translationRepository
     * @param IlluminateCollection $translationsCollection
     * @param Translation $translation
     */
    public function __construct(
        TranslationRepositoryInterface $translationRepository,
        IlluminateCollection $translationsCollection,
        Translation $translation
    ) {
        $this->translationRepository = $translationRepository;
        $this->translationsCollection = $translationsCollection;
        $this->translation = $translation;
    }

    /**
     * @param string $group
     * @param string $locale
     * @return IlluminateCollection
     */
    public function get(string $group, string $locale): IlluminateCollection
    {
        $groups = $this->translationRepository->findAllKeyFieldByGroup($group);

        $groupAndLocalTranslations = $this->translationRepository->findAllByGroupAndLocale($group, $locale);

        foreach ($groups as $groupItem) {
            $this->loadTranslationsCollection($groupAndLocalTranslations, $groupItem, $locale);
        }

        return $this->translationsCollection;
    }

    /**
     * @param Collection $groupAndLocalTranslations
     * @param Translation $group
     * @param string $locale
     */
    protected function loadTranslationsCollection(Collection $groupAndLocalTranslations, Translation $group, string $locale): void
    {
        $translationModel = $this->getFromTranslations($groupAndLocalTranslations, $group->group, $group->item);

        if($translationModel === null) {
            $translationModel = $this->makeTranslation($group->group, $locale, $group->item);
        }

        $this->translationsCollection->push($translationModel);
    }

    /**
     * @param Collection $translations
     * @param string $group
     * @param string $item
     * @return IlluminateCollection|null
     */
    protected function getFromTranslations(Collection $translations, string $group, string $item)
    {
        return $translations->filter(function (Translation $translation) use ($group, $item) {
            return $translation->group == $group && $translation->item == $item;
        })->first();
    }

    /**
     * @param string $group
     * @param string $locale
     * @param string $item
     * @return \Illuminate\Database\Eloquent\Model|Translation
     */
    protected function makeTranslation(string $group, string $locale, string $item): Translation
    {
        return $this->translation->newQuery()->make([
            'group' => $group,
            'item' => $item,
            'locale' => $locale,
            'namespace' => '*',
            'text' => $group . '.' . $item,
        ]);
    }
}
