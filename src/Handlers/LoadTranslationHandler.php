<?php

namespace Stilldesign\Translations\Handlers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Stilldesign\Translations\Contracts\LoadTranslationsHandlerInterface;
use Stilldesign\Translations\Contracts\TranslationRepositoryInterface;
use Stilldesign\Translations\Models\Translation;

class LoadTranslationHandler implements LoadTranslationsHandlerInterface
{
    protected $repository;
    protected $translation;

    protected $items = [];

    public function __construct(TranslationRepositoryInterface $repository, Translation $translation)
    {
        $this->repository = $repository;
        $this->translation = $translation;
    }

    public function load($translations, string $locale)
    {
        foreach ($translations as $group => $translation) {
            $this->flatten($translation, $group);
        }

        foreach ($this->items as $key => $value) {

            list($group, $key) = explode('.', $key, 2);

            try {
                $this->repository->findByGroupLocaleAndItem($group, $locale, $key);
            } catch (ModelNotFoundException $e) {
                $translation = $this->translation->newQuery()->make([
                    'locale' => $locale,
                    'group' => $group,
                    'item' => $key,
                    'text' => $value,
                ]);

                $this->repository->save($translation);
            }

        }
    }

    private function flatten($item, $key = '')
    {
        if(is_object($item)) {
            foreach($item as $subKey => $subItem) {
                $this->flatten($subItem, $key . '.' . $subKey);
            }
        } else {
            $this->items[$key] = $item;
        }
    }
}
