<?php

namespace Stilldesign\Translations\Handlers;

use Illuminate\Database\Eloquent\Collection;
use Stilldesign\Translations\Contracts\FindGroupsHandlerInterface;
use Stilldesign\Translations\Contracts\TranslationRepositoryInterface;

/**
 * @property TranslationRepositoryInterface $translationRepository
 */
class FindGroups implements FindGroupsHandlerInterface
{
    protected $translationRepository;

    /**
     * FindGroups constructor.
     * @param TranslationRepositoryInterface $translationRepository
     */
    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * @return Collection
     */
    public function find(): Collection
    {
        return $this->translationRepository->findAllGroup();
    }
}
