<?php

declare(strict_types=1);

namespace App\Http\DataTables\Users;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Nexus\Monolith\DataTable\Blueprints\DataTableBlueprint;
use Nexus\Monolith\DataTable\Blueprints\DataColumn;
use UnexpectedValueException;

class UsersDataTable extends DataTableBlueprint
{
    /**
     * @var array<string, list<string>>
     */
    private const array SORTABLE_COLUMNS = [
        'fullname' => ['firstname', 'lastname'],
        'email' => ['email'],
    ];

    public function __construct(private readonly Request $request)
    {
        parent::__construct($request);
    }

    protected function getResourceClass(): string
    {
        return UserResource::class;
    }

    /** @return Builder<User> */
    protected function source(): Builder
    {
        $query = (new User)->newQueryWithoutRelationships();
        $sortBy = $this->requestedSortColumn();

        if ($sortBy === null) {
            $query->orderBy('id');

            return $query;
        }

        $direction = $this->requestedSortDirection();

        foreach (self::SORTABLE_COLUMNS[$sortBy] as $column) {
            $query->orderBy($column, $direction);
        }

        $query->orderBy('id', $direction);

        return $query;
    }

    protected function columns(): array
    {
        return [
            new DataColumn('selectable')->selectable(),
            new DataColumn('fullname')->label($this->label('frontend.data_table.columns.fullname')),
            new DataColumn('email')->label($this->label('frontend.data_table.columns.email')),
        ];
    }

    private function label(string $key): string
    {
        $label = __($key);

        if (! is_string($label)) {
            throw new UnexpectedValueException("The translation [{$key}] must be a string.");
        }

        return $label;
    }

    private function requestedSortColumn(): ?string
    {
        $sortBy = $this->request->string('sortBy')->toString();

        if (! array_key_exists($sortBy, self::SORTABLE_COLUMNS)) {
            return null;
        }

        foreach ($this->columns() as $column) {
            $definition = $column->toArray();

            if ($definition['name'] === $sortBy && $definition['sortable'] === true) {
                return $sortBy;
            }
        }

        return null;
    }

    private function requestedSortDirection(): string
    {
        return $this->request->string('sortDirection')->lower()->toString() === 'desc'
            ? 'desc'
            : 'asc';
    }
}
