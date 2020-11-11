<?php

namespace Stilldesign\Translations\Contracts;

interface LoadTranslationsHandlerInterface
{
    public function load($translations, string $locale);
}
