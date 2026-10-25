<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Nexus\Enums\Page\ThemeVariant;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Nexus\Auth\AuthorizableModel;
use Nexus\Enums\Auth\AuthType;
use Modules\Enterprise\Concerns\HasEnterpriseRelationships;

/**
 * @property int $id
 * @property string $uuid
 * @property string $firstname
 * @property string $lastname
 * @property string|null $middlename
 * @property string|null $username
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property AuthType $auth
 * @property array<array-key, mixed>|null $ia Identity attributes for external auth
 * @property ThemeVariant $theme
 * @property string $locale
 * @property string $timezone
 * @property string|null $bio
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $suspended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User authorizable()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User newModelQuery()
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User query()
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereAuth($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereAvatar($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereBio($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereCreatedAt($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereDeletedAt($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereEmail($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereEmailVerifiedAt($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereFirstname($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereIa($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereId($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereLastname($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereLocale($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereMiddlename($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User wherePassword($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereRememberToken($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereSuspendedAt($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereTheme($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereTimezone($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereUpdatedAt($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereUsername($value)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Illuminate\Database\Eloquent\Model
 */
#[Hidden(['password', 'remember_token', 'ia'])]
class User extends AuthorizableModel
{
    use HasEnterpriseRelationships;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    public const string TABLE = 'users';

    protected $table = self::TABLE;

    protected $fillable = [
        'uuid',
        'firstname',
        'lastname',
        'middlename',
        'username',
        'email',
        'password',
        'locale',
        'timezone',
        'bio',
        'avatar',
        'theme',
        'auth',
        'suspended_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'theme' => ThemeVariant::class,
            'auth' => AuthType::class,
            'ia' => 'array',
            'suspended_at' => 'datetime',
        ];
    }
}
