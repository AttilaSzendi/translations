<?php

namespace Tests\Integration;

use Stilldesign\Translations\Handlers\LoadTranslationHandler;
use Stilldesign\Translations\Handlers\UpdateTranslationHandler;
use Stilldesign\Translations\Models\Translation;
use Stilldesign\Translations\Repositories\EloquentTranslationRepository;
use Stilldesign\Translations\Tests\TestCase;

class LoadTranslationHandlerTest extends TestCase
{
    /**
     * @var LoadTranslationHandler
     */
    private $underTest;

    public function setUp()
    {
        parent::setUp();

        $translationRepository = new EloquentTranslationRepository(new Translation());

        $translationModel = new Translation();

        $this->underTest = new LoadTranslationHandler($translationRepository, $translationModel);
    }

    /**
     * @test
     */
    public function load_should_load_translations_array()
    {

        $originalData = [
            'locale' => 'hu',
            'namespace' => '*',
            'group' => 'existing',
            'item' => 'existing.item',
            'text' => 'original value',
        ];

        factory(Translation::class)->create($originalData);

        $json = '{
            "analysis": {
                "select-graph": "Grafikon kiválasztása",
                "title": "Elemzések"
            },
            "existing": {
                "existing": {
                    "item": "new Value"
                }
            },
            "app": {
                "all": "Mind",
                "are-you-sure": "Biztosan folytatni szeretné a műveletet?",
                "loading": "Töltés",
                "no": "Nem",
                "no-result": "Nincs találat",
                "no-results-found": "Nincs találat",
                "order": "Rendezés",
                "please-choose": "Kérem válasszon!",
                "related-documents": "Kapcsolódó dokumentumok",
                "system-message": "Rendszerüzenet",
                "total": "Találatok száma",
                "yes": "Igen"
            },
            "attributes": {
                "activity": "Tevékenység",
                "address": {
                    "address": "Közterület neve",
                    "address-type": "Jellege",
                    "building": "Épület",
                    "city": "Település",
                    "door": "Ajtó",
                    "floor": "Emelet",
                    "number": "Házszám",
                    "same-as-billing": "Megegyezik a számlázási címmel",
                    "same-as-company": "Megegyezik a székhely címével",
                    "stairway": "Lépcsőház",
                    "zip": "Irányítószám"
                }
            }
        }';

        $array = json_decode($json);

        $this->underTest->load($array, 'hu');

        $this->assertDatabaseHas('translator_translations', [
            'group' => 'attributes',
        ]);

        $this->assertDatabaseHas('translator_translations', [
            'group' => 'attributes',
            'item' => 'address.address',
            'text' => 'Közterület neve',
        ]);

        $this->assertDatabaseHas('translator_translations', $originalData);

    }

}
