<?php

namespace Tests\Unit;

use Stilldesign\Translations\Models\Translation;
use Stilldesign\Translations\Tests\TestCase;
use Stilldesign\Translations\Transformers\TranslationsTransformer;

class TranslationsTransformerTest extends TestCase
{

    /**
     * @var TranslationsTransformer
     */
    protected $underTest;

    protected function setUp()
    {
        parent::setUp();

        $this->underTest = new TranslationsTransformer();

    }

    /**
     * @test
     */
    public function should_transformed_data_if_group_only_string_format()
    {

        $currentItem = 1;
        $groupItem = 1;
        $translation = factory(Translation::class, 20)->make()->each(function(Translation $translation) use (&$currentItem, &$groupItem) {
            $group = 'group' . $groupItem;
            $item = 'item' . $currentItem;
            $translation->item = $item;
            $translation->group = $group;
            $currentItem++;
            if($currentItem == 10) {
                $groupItem++;
            }

            if($currentItem == 15) {
                $translation->item = 'item15.subitem';
            }

            if($currentItem == 16) {
                $translation->item = 'item15.subitem2';
            }

            if($currentItem == 17) {
                $translation->item = 'item17.subitem.subsubitem';
            }

        });

        $actual = $this->underTest->transform($translation);

        $this->assertTrue(isset($actual['group1']['item1']));
        $this->assertTrue(isset($actual['group2']['item11']));
        $this->assertTrue(isset($actual['group2']['item15']['subitem']));
        $this->assertTrue(isset($actual['group2']['item15']['subitem2']));
        $this->assertTrue(isset($actual['group2']['item17']['subitem']['subsubitem']));
    }
}
