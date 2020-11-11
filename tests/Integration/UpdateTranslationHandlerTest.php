<?php

namespace Tests\Integration;

use Stilldesign\Translations\Handlers\UpdateTranslationHandler;
use Stilldesign\Translations\Models\Translation;
use Stilldesign\Translations\Models\TranslationDataModel;
use Stilldesign\Translations\Repositories\EloquentTranslationRepository;
use Stilldesign\Translations\Tests\TestCase;

class UpdateTranslationHandlerTest extends TestCase
{
    /**
     * @var UpdateTranslationHandler
     */
    private $underTest;
    private $translationModel;

    public function setUp()
    {
        parent::setUp();

        $translationRepository = new EloquentTranslationRepository(new Translation());
        $this->translationModel = new Translation();

        $this->underTest = new UpdateTranslationHandler($translationRepository, $this->translationModel);
    }

    /**
     * @test
     */
    public function update_should_create_new_entry_if_dos_not_exists_record()
    {
        $translations = [];

        $translationDataObject = new TranslationDataModel();
        $translationDataObject->locale = 'en';
        $translationDataObject->group = 'group';
        $translationDataObject->item = 'item';
        $translationDataObject->text = 'value';

        $translations[] = $translationDataObject;

        $this->underTest->update($translations);

        $this->assertDatabaseHas($this->translationModel->getTable(), [
            'locale' => 'en',
        ]);
    }

    /**
     * @test
     */
    public function update_should_update_entry_if_exists_record()
    {
        $translations = [];

        factory(Translation::class)->create([
            'locale' => 'en',
            'group' => 'group',
            'item' => 'item',
            'text' => 'value',
        ]);

        $modifiedValue = 'modified value';

        $translationDataObject = new TranslationDataModel();
        $translationDataObject->locale = 'en';
        $translationDataObject->group = 'group';
        $translationDataObject->item = 'item';
        $translationDataObject->text = $modifiedValue;

        $translations[] = $translationDataObject;

        $this->underTest->update($translations);

        $this->assertDatabaseHas($this->translationModel->getTable(), [
            'text' => $modifiedValue,
        ]);
    }
}
