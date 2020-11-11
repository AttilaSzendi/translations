<?php

namespace Stilldesign\Translations\Handlers;

use Stilldesign\Translations\Contracts\GetAllLocaleTranslationsInterface;
use Stilldesign\Translations\Contracts\TranslationRepositoryInterface;
use Illuminate\Support\Collection;
use Stilldesign\Translations\Models\Translation;

class GetAllLocaleTranslations implements GetAllLocaleTranslationsInterface
{
    protected $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * @param $locale
     * @return Collection
     */
    public function get($locale): Collection
    {
        $translations = $this->translationRepository->findAllByLocale($locale);

        foreach($translations as $translation) {
            /* @var Translation $translation */
            $translation->text = empty($translation->text) ? $translation->group . '.' . $translation->item : $translation->text;
        }

        return $translations;
    }
}
