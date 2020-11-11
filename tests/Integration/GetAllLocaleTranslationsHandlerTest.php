<?php

namespace Tests\Integration;

use Stilldesign\Translations\Handlers\GetAllLocaleTranslations;
use Stilldesign\Translations\Models\Translation;
use Stilldesign\Translations\Repositories\EloquentTranslationRepository;
use Stilldesign\Translations\Tests\TestCase;

class GetAllLocaleTranslationsHandlerTest extends TestCase
{
    /**
     * @var GetAllLocaleTranslations
     */
    private $underTest;

    public function setUp()
    {
        parent::setUp();

        $translationRepository = new EloquentTranslationRepository(new Translation());

        $this->underTest = new GetAllLocaleTranslations($translationRepository);
    }

    /**
     * @test
     */
    public function get_should_all_locale_translations()
    {
        $localeEn = 'en';
        $localeEs = 'es';
        $group = 'group';

        factory(Translation::class, 10)->create(['locale' => $localeEn, 'group' => $group]);
        factory(Translation::class, 10)->create(['locale' => $localeEs, 'group' => $group]);

        $actual = $this->underTest->get($localeEn);

        $this->assertEquals(10, $actual->count());
    }
}