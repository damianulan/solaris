<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Nexus\Monolith\DataTable\Blueprints\ActionColumn;
use Nexus\Monolith\DataTable\Blueprints\DataTableBlueprint;
use Tests\TestCase;

uses(TestCase::class);

it('serializes resource-specific Vue button props with each row', function () {
    $table = new ActionColumnSerializationDataTable(collect([
        new ActionColumnSerializationRow(
            id: 42,
            profileUrl: '/users/42',
        ),
    ]));

    $data = $table->toArray();
    $paginator = $data['paginator'];

    if (! is_array($paginator)) {
        throw new \UnexpectedValueException('The paginator must be serialized as an array.');
    }

    expect($paginator['data'] ?? null)->toBe([
        [
            'id' => 42,
            'profileUrl' => '/users/42',
            'actions' => [
                [
                    'href' => '/users/42',
                    'prependIcon' => 'bi-eye',
                    'variant' => 'text',
                ],
            ],
        ],
    ]);
});

final readonly class ActionColumnSerializationRow
{
    public function __construct(
        public int $id,
        public string $profileUrl,
    ) {
    }
}

final class ActionColumnSerializationResource extends JsonResource
{
    /** @return array{id: int, profileUrl: string} */
    public function toArray(Request $request): array
    {
        if (! $this->resource instanceof ActionColumnSerializationRow) {
            return [
                'id' => 0,
                'profileUrl' => '',
            ];
        }

        return [
            'id' => $this->resource->id,
            'profileUrl' => $this->resource->profileUrl,
        ];
    }
}

final class ActionColumnSerializationDataTable extends DataTableBlueprint
{
    /** @param Collection<int, ActionColumnSerializationRow> $resources */
    public function __construct(private readonly Collection $resources)
    {
        parent::__construct(Request::create('/'));
    }

    protected function getResourceClass(): string
    {
        return ActionColumnSerializationResource::class;
    }

    /** @return Collection<int, ActionColumnSerializationRow> */
    protected function source(): Collection
    {
        return $this->resources;
    }

    protected function columns(): array
    {
        return [
            (new ActionColumn)->addButton(fn (array $resource): array => [
                'href' => $resource['profileUrl'],
                'prependIcon' => 'bi-eye',
                'variant' => 'text',
            ]),
        ];
    }
}
