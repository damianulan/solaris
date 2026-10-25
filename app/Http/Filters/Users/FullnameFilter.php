<?php

declare(strict_types=1);

namespace App\Http\Filters\Users;

use Illuminate\Contracts\Database\Query\Builder;
use Nexus\Monolith\Filtering\Blueprints\Filter;
use Nexus\Monolith\Filtering\Concerns\QueryTextSearch;
use Nexus\Monolith\Filtering\Contracts\Types\TextFilter;
use UnexpectedValueException;

class FullnameFilter extends Filter implements TextFilter
{
    use QueryTextSearch;

    public function getLabel(): string
    {
        $label = __('frontend.data_table.columns.fullname');

        if (! is_string($label)) {
            throw new UnexpectedValueException('The full name filter label must be a string.');
        }

        return $label;
    }

    public function withQuery(Builder $query): void
    {
        $value = $this->getValue();

        $this->nameSearch(
            $query,
            is_string($value) ? $value : null,
            ['firstname', 'middlename', 'lastname'],
        );
    }
}
