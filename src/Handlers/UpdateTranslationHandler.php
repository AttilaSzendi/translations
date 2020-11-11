<?php

namespace Stilldesign\Translations\Handlers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Stilldesign\Translations\Contracts\TranslationRepositoryInterface;
use Stilldesign\Translations\Contracts\UpdateTranslationHandlerInterface;
use Stilldesign\Translations\Models\Translation;
use Stilldesign\Translations\Models\TranslationDataModel;

class UpdateTranslationHandler implements UpdateTranslationHandlerInterface
{
    protected $translationRepository;
    protected $translation;

    public function __construct(TranslationRepositoryInterface $translationRepository, Translation $translation)
    {
        $this->translationRepository = $translationRepository;
        $this->translation = $translation;
    }

    public function update(array $translations)
    {
        foreach($translations as $translation) {
            $this->updateTranslation($translation);
        }
    }

    protected function updateTranslation(TranslationDataModel $translationDataModel)
    {
        try {
            $translationModel = $this->translationRepository->findByGroupLocaleAndItem($translationDataModel->group, $translationDataModel->locale, $translationDataModel->item);
            $translationModel->text = $translationDataModel->text;
        } catch (ModelNotFoundException $e) {
            $translationModel = $this->translation->newQuery()->make([
                'locale' => $translationDataModel->locale,
                'namespace' => $translationDataModel->namespace,
                'group' => $translationDataModel->group,
                'item' => $translationDataModel->item,
                'text' => $translationDataModel->text,
            ]);
        }

        $this->translationRepository->save($translationModel);
    }
}
