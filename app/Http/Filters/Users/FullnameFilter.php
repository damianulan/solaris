<?php

namespace App\Http\Filters\Users;

use Nexus\Monolith\Filtering\Blueprints\Filter;
use Illuminate\Contracts\Database\Query\Builder;
use Nexus\Monolith\Filtering\Concerns\QueryTextSearch;
use Nexus\Monolith\Filtering\Contracts\Types\TextFilter;

class FullnameFilter extends Filter implements TextFilter
{
    use QueryTextSearch;

    public function getLabel(): string
    {
        return 'Imię i nazwisko';
    }

    public function withQuery(Builder $query): void
    {
        $this->nameSearch($query, $this->getValue(), ['firstname', 'middlename', 'lastname']);
    }
}
