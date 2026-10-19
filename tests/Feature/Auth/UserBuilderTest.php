<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Nexus\Auth\Builders\UserBuilder;
use Tests\TestCase;

class UserBuilderTest extends TestCase
{
    public function test_authorizable_requires_a_null_suspended_at_timestamp(): void
    {
        $query = User::query();

        $this->assertInstanceOf(UserBuilder::class, $query);

        $query->authorizable();

        $this->assertSame([
            'type' => 'Null',
            'column' => 'suspended_at',
            'boolean' => 'and',
        ], $query->getQuery()->wheres[0]);
    }
}
