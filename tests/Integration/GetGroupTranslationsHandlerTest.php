<?php

namespace Tests\Integration;

use Illuminate\Support\Collection;
use Stilldesign\Translations\Handlers\GetGroupTranslations;
use Stilldesign\Translations\Models\Translation;
use Stilldesign\Translations\Repositories\EloquentTranslationRepository;
use Stilldesign\Translations\Tests\TestCase;

class GetGroupTranslationsHandlerTest extends TestCase
{
    /**
     * @var GetGroupTranslations
     */
    private $underTest;

    public function setUp()
    {
        parent::setUp();

        $translationRepository = new EloquentTranslationRepository(new Translation());
        $collection = new Collection();
        $translationModel = new Translation();

        $this->underTest = new GetGroupTranslations($translationRepository, $collection, $translationModel);
    }

    /**
     * @test
     */
    public function get_should_all_entry_in_group_and_locale_with_empty_value_is_not_translated()
    {
        $localeEn = 'en';
        $localeEs = 'es';
        $group = 'group';
        $item1 = 'item1';
        $item2 = 'item2';

        factory(Translation::class)->create(['locale' => $localeEn, 'group' => $group, 'item' => $item1]);
        factory(Translation::class)->create(['locale' => $localeEs, 'group' => $group, 'item' => $item2]);

        $actual = $this->underTest->get($group, $localeEs);

        $this->assertEquals(2, $actual->count());
    }

}