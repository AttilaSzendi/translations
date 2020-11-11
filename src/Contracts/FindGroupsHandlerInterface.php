<?php

namespace Stilldesign\Translations\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface FindGroupsHandlerInterface
{
    /**
     * @return Collection
     */
    public function find(): Collection;
}
