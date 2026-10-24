<?php

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Connection;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Query\Builder;
use Nexus\Monolith\Filtering\Concerns\QueryTextSearch;

/**
 * @param  Builder  $query
 * @param  string  $text
 * @param  array<int, string>  $fields
 * @return Builder
 */
function applyTextSearch(Builder $query, string $text, array $fields): Builder
{
    $search = new class () {
        use QueryTextSearch;

        /**
         * @param  BuilderContract  $query
         * @param  string  $text
         * @param  array<int, string>  $fields
         */
        public function apply(BuilderContract $query, string $text, array $fields): void
        {
            $this->searchText($query, $text, $fields);
        }
    };

    $search->apply($query, $text, $fields);

    return $query;
}

it('searches concatenated fields regardless of term order', function () {
    $connection = new Connection(null, '', '', ['driver' => 'sqlite']);

    $query = applyTextSearch(
        $connection->table('people'),
        'Lovelace Ada',
        ['first_name', 'last_name'],
    );

    expect($query->toSql())
        ->toBe('select * from "people" where coalesce("first_name", \'\') || \' \' || coalesce("last_name", \'\') like ? and coalesce("first_name", \'\') || \' \' || coalesce("last_name", \'\') like ?')
        ->and($query->getBindings())
        ->toBe(['%Lovelace%', '%Ada%']);
});

it('uses concat ws for database drivers that support it', function () {
    $connection = new MySqlConnection(null, '', '', ['driver' => 'mysql']);

    $query = applyTextSearch(
        $connection->table('people'),
        'Ada Lovelace',
        ['last_name', 'first_name'],
    );

    expect($query->toSql())
        ->toContain("concat_ws(' ', `last_name`, `first_name`) like ?")
        ->and($query->getBindings())
        ->toBe(['%Ada%', '%Lovelace%']);
});
