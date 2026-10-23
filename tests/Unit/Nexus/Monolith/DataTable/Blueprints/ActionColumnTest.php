<?php

declare(strict_types=1);

use Nexus\Monolith\DataTable\Blueprints\ActionColumn;

it('serializes action column properties for Vue', function () {
    $column = (new ActionColumn)->label('Actions');

    expect($column->toArray())->toBe([
        'name' => 'actions',
        'label' => 'Actions',
        'sortable' => false,
        'alignment' => 'right',
        'type' => 'action',
    ]);
});

it('resolves each button with the resource row as Vue props', function () {
    $resource = [
        'id' => 42,
        'name' => 'Ada Lovelace',
    ];
    $receivedResources = [];
    $column = (new ActionColumn)
        ->addButton(function (array $row) use (&$receivedResources): array {
            $receivedResources[] = $row;

            return [
                'href' => '/users/42',
                'prependIcon' => 'bi-eye',
                'variant' => 'text',
            ];
        })
        ->addButton(function (array $row) use (&$receivedResources): array {
            $receivedResources[] = $row;

            return [
                'disabled' => $row['id'] === 42,
                'icon' => 'bi-trash',
            ];
        });

    $buttons = $column->buttonsFor($resource);

    expect($buttons)->toBe([
        [
            'href' => '/users/42',
            'prependIcon' => 'bi-eye',
            'variant' => 'text',
        ],
        [
            'disabled' => true,
            'icon' => 'bi-trash',
        ],
    ]);
    expect($receivedResources)->toBe([$resource, $resource]);
});
