<?php

namespace Tests\Integration;

use Stilldesign\Translations\Models\Language;
use Stilldesign\Translations\Models\Translation;
use Stilldesign\Translations\Repositories\EloquentTranslationRepository;
use Stilldesign\Translations\Tests\TestCase;

class EloquentLanguageRepositoryTest extends TestCase
{
    /**
     * @var EloquentTranslationRepository
     */
    private $underTest;

    public function setUp()
    {
        parent::setUp();

        $this->underTest = new EloquentTranslationRepository(new Translation());
    }

    /**
     * @test
     */
    public function findAllGroup_should_return_all_group()
    {
        $groups = ['group1', 'group2', 'group3', 'group4'];
        $locales = ['en', 'es'];

        $this->createLanguages($locales);

        factory(Translation::class, 100)->make()->each(function (Translation $translation) use ($locales, $groups) {
            $translation->locale = $locales[array_rand($locales)];
            $translation->group = $groups[array_rand($groups)];
            $translation->save();
        });

        $actual = $this->underTest->findAllGroup();

        $this->assertEquals(count($groups), $actual->count());
        $this->assertTrue($actual->contains('group', 'group1'));
        $this->assertTrue($actual->contains('group', 'group2'));
        $this->assertTrue($actual->contains('group', 'group3'));
        $this->assertTrue($actual->contains('group', 'group4'));
    }

    /**
     * @test
     */
    public function findAllKeyField_should_all_translation_entry_in_group_with_grouped_key_fields()
    {
        $localeEn = 'en';
        $localeEs = 'es';

        $group = 'group';

        $this->createLanguages([$localeEn, $localeEs]);

        $this->createTranslation($localeEn, $group, 'item1');
        $this->createTranslation($localeEs, $group, 'item1');
        $this->createTranslation($localeEs, $group, 'item2');
        $this->createTranslation($localeEs, $group, 'item2', 'namespace');

        $actual = $this->underTest->findAllKeyFieldByGroup($group);

        $this->assertEquals(3, $actual->count());
    }

    /**
     * @test
     */
    public function findByGroupAndLocale_should_all_entry_by_group_and_locale()
    {
        $localeEn = 'en';
        $localeEs = 'es';

        $group1 = 'group1';
        $group2 = 'group2';

        $this->createLanguages([$localeEn, $localeEs]);

        $this->createTranslation($localeEn, $group1, 'item');
        $this->createTranslation($localeEn, $group2, 'item');
        $this->createTranslation($localeEs, $group1, 'item');
        $this->createTranslation($localeEs, $group2, 'item');

        $actual = $this->underTest->findAllByGroupAndLocale($group1, $localeEn);

        $this->assertEquals(1, $actual->count());
    }

    /**
     * @test
     */
    public function save_should_create_new_entry_in_db_if_not_present()
    {
        $locale = 'en';
        $group = 'group';

        $this->createLanguages([$locale]);

        $translation = $this->makeTranslation($locale, $group);

        $this->underTest->save($translation);

        // THEN
        $this->assertDatabaseHas($translation->getTable(), [
            'group' => $group,
            'locale' => $locale,
            'item' => $translation->item
        ]);
    }

    /**
     * @test
     */
    public function save_should_update_entry_in_db_if_present()
    {
        $locale = 'en';
        $group = 'group';
        $item = 'item';

        $modifiedValue = 'modified value';

        $this->createLanguages([$locale]);
        $translation = $this->createTranslation($locale, $group, $item);

        $translation->fill(['text' => $modifiedValue]);

        $this->underTest->save($translation);

        $this->assertDatabaseHas($translation->getTable(), [
            'locale' => $locale,
            'group' => $group,
            'item' => $item,
            'text' => $modifiedValue,
        ]);
    }

    /**
     * @test
     */
    public function findByGroupLocaleAndItem_should_entry_by_group_locale_and_item()
    {
        $locale = 'en';
        $group = 'group';
        $item = 'item';

        $this->createLanguages([$locale]);

        $this->createTranslation($locale, $group, $item);

        $actual = $this->underTest->findByGroupLocaleAndItem($group, $locale, $item);

        $this->assertEquals($group, $actual->group);
    }

    /**
     * @test
     */
    public function findAllByLocale_should_all_entry_by_locale()
    {
        $localeEn = 'en';
        $localeEs = 'es';

        factory(Translation::class, 10)->create(['locale' => $localeEn]);
        factory(Translation::class, 10)->create(['locale' => $localeEs]);

        $actual = $this->underTest->findAllByLocale($localeEn);

        $this->assertEquals(10, $actual->count());
    }

    /**
     * @test
     */
    public function findAllByKeyword_should_all_entry_by_locale()
    {
        $text = "Test value";

        factory(Translation::class, 10)->create(['text' => "don't find it"]);
        factory(Translation::class, 1)->create(['text' => $text]);

        $actual = $this->underTest->findAllByKeyword("est v");

        $this->assertEquals(1, $actual->count());
    }

    /**
     * @param array $locales
     */
    private function createLanguages(array $locales)
    {
        foreach($locales as $locale) {
            factory(Language::class)->create(['locale' => $locale]);
        }
    }

    /**
     * @param string $locale
     * @param string $group
     * @param string $item
     * @param string $namespace
     * @return Translation
     */
    private function createTranslation(string $locale, string $group, string $item, string $namespace = '*'): Translation
    {
        return factory(Translation::class)->create([
            'locale' => $locale,
            'group' => $group,
            'item' => $item,
            'namespace' => $namespace
        ]);
    }

    /**
     * @param $locale
     * @param $group
     * @return Translation
     */
    private function makeTranslation($locale, $group): Translation
    {
        return factory(Translation::class)->make([
            'locale' => $locale,
            'group' => $group
        ]);
    }
}