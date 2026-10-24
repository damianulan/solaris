<?php

namespace App\Http\Filters\Users;

use Nexus\Monolith\Filtering\Blueprints\Filter;
use Illuminate\Contracts\Database\Query\Builder;

class FullnameFilter extends Filter
{
    public function getLabel(): string
    {
        return 'Imię i nazwisko';
    }

    public function withQuery(Builder $query): Builder
    {
        return $query->where('fullname', 'like', '%' . $this->getValue() . '%');
    }
}
