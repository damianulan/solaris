<?php

declare(strict_types=1);

namespace App\Http\DataTables\Users;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Nexus\Monolith\DataTable\Blueprints\ActionColumn;
use Nexus\Monolith\DataTable\Blueprints\DataTableBlueprint;
use Nexus\Monolith\DataTable\Blueprints\DataColumn;
use Nexus\Monolith\DataTable\Blueprints\SelectableColumn;
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
            new SelectableColumn,
            new DataColumn('fullname')->label($this->label('frontend.data_table.columns.fullname')),
            new DataColumn('email')->label($this->label('frontend.data_table.columns.email')),
            (new ActionColumn)
                ->label($this->label('frontend.data_table.columns.actions'))
                ->addButton(fn (array $resource): array => [
                    'color' => 'primary',
                    'href' => $this->resourceUrl($resource, 'editUrl'),
                    'icon' => 'bi-pencil-fill',
                    'title' => $this->label('frontend.data_table.actions.edit'),
                    'variant' => 'text',
                ])
                ->addButton(fn (array $resource): array => [
                    'color' => 'warning',
                    'confirm' => $this->label('frontend.data_table.actions.suspend_confirmation'),
                    'disabled' => ($resource['isSuspended'] ?? false) === true
                        || $this->isAuthenticatedUser($resource),
                    'href' => $this->resourceUrl($resource, 'suspendUrl'),
                    'icon' => 'bi-person-x-fill',
                    'method' => 'post',
                    'title' => ($resource['isSuspended'] ?? false) === true
                        ? $this->label('frontend.data_table.actions.suspended')
                        : ($this->isAuthenticatedUser($resource)
                            ? $this->label('frontend.data_table.actions.self_suspend')
                            : $this->label('frontend.data_table.actions.suspend')),
                    'variant' => 'text',
                ])
                ->addButton(fn (array $resource): array => [
                    'color' => 'error',
                    'confirm' => $this->label('frontend.data_table.actions.delete_confirmation'),
                    'disabled' => $this->isAuthenticatedUser($resource),
                    'href' => $this->resourceUrl($resource, 'deleteUrl'),
                    'icon' => 'bi-trash-fill',
                    'method' => 'delete',
                    'title' => $this->isAuthenticatedUser($resource)
                        ? $this->label('frontend.data_table.actions.self_delete')
                        : $this->label('frontend.data_table.actions.delete'),
                    'variant' => 'text',
                ]),
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

    /** @param array<string, mixed> $resource */
    private function resourceUrl(array $resource, string $key): string
    {
        $url = $resource[$key] ?? null;

        if (! is_string($url)) {
            throw new UnexpectedValueException("The resource [{$key}] must be a string.");
        }

        return $url;
    }

    /** @param array<string, mixed> $resource */
    private function isAuthenticatedUser(array $resource): bool
    {
        $authenticatedUser = $this->request->user();

        return $authenticatedUser instanceof User
            && ($resource['id'] ?? null) === $authenticatedUser->getKey();
    }

    /** @return 'asc'|'desc' */
    private function requestedSortDirection(): string
    {
        return $this->request->string('sortDirection')->lower()->toString() === 'desc'
            ? 'desc'
            : 'asc';
    }
}
