<?php

namespace Stilldesign\Translations\Transformers;

use Illuminate\Support\Collection;

class TranslationsTransformer
{
    protected $transformedTranslation = [];

    public function transform(Collection $translations)
    {
        $translations = $this->collectionToArray($translations);

        foreach ($translations as $group => $value) {
            $this->translationToArray($group, $value);
        }

        return $this->transformedTranslation;
    }

    protected function collectionToArray(Collection $collection)
    {
        $translationsArray = [];

        foreach ($collection as $translation) {
            $key = $translation->group . '.' . $translation->item;
            $translationsArray[$key] = $translation->text;
        }

        return $translationsArray;
    }

    protected function translationToArray($group, $value)
    {
        $keys = explode('.', $group);
        $groupArray = [];
        $levelArray = &$groupArray;
        foreach ($keys as $key) {
            if(!isset($levelArray[$key])) {
                $levelArray[$key] = array();
            }
            $levelArray = &$levelArray[$key];
        }

        $levelArray = $value;

        unset($levelArray);

        $this->transformedTranslation = array_merge_recursive($this->transformedTranslation, $groupArray);
    }
}