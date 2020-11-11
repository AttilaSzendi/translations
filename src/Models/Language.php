<?php

namespace Stilldesign\Translations\Models;

use Illuminate\Support\Carbon;

/**
 * @property string $locale
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property Translation[] $translations
 */
class Language extends \Waavi\Translation\Models\Language
{

}