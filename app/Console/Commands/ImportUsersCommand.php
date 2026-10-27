<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Clients\UsersApiClient;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use UnexpectedValueException;
use Illuminate\Support\Str;

#[Signature('users:import')]
#[Description('Import users from the users API')]
class ImportUsersCommand extends Command
{
    public function handle(UsersApiClient $usersApiClient): int
    {
        $users = $usersApiClient()
            ->throw()
            ->collect('data');

        $fillable = array_flip((new User)->syncable());

        $users->each(function (mixed $attributes) use ($fillable): void {
            if (! is_array($attributes)) {
                throw new UnexpectedValueException('The users API returned an invalid user.');
            }

            if (!isset($attributes['username'])) {
                $attributes['username'] = Str::ascii(
                    Str::lower($attributes['firstname'] . '.' . $attributes['lastname'])
                );
            }

            User::query()->updateOrCreate([
                'username' => $attributes['username']
                ], array_intersect_key($attributes, $fillable));
        });

        $this->components->info("Imported {$users->count()} users.");

        return self::SUCCESS;
    }
}
