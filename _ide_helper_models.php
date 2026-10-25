<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace Modularis\Models{
/**
 * @property int $id
 * @property string $slug
 * @property int $display_order
 * @property string $version
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property ModuleType $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleModel whereVersion($value)
 * @mixin \Eloquent
 */
	class ModuleModel extends \Eloquent implements \Modularis\Contracts\ModuleModelContract {}
}

namespace Nexus\Auth{
/**
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static UserBuilder<static>|AuthorizableModel authorizable()
 * @method static UserBuilder<static>|AuthorizableModel newModelQuery()
 * @method static UserBuilder<static>|AuthorizableModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorizableModel onlyTrashed()
 * @method static UserBuilder<static>|AuthorizableModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorizableModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorizableModel withoutTrashed()
 * @mixin \Eloquent
 */
	class AuthorizableModel extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\Access\Authorizable, \Illuminate\Contracts\Auth\CanResetPassword {}
}

namespace Sentinel\Models{
/**
 * @property-read Collection<int, \Sentinel\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static Builder<static>|Permission newModelQuery()
 * @method static Builder<static>|Permission newQuery()
 * @method static Builder<static>|Permission query()
 * @method static Builder<static>|Permission whereAssignable()
 * @method static Builder<static>|Permission whereSlug(...$slugs)
 * @mixin \Eloquent
 */
	class Permission extends \Eloquent implements \Sentinel\Contracts\PermissionContract {}
}

namespace Sentinel\Models{
/**
 * @property-read Collection<int, \Sentinel\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @method static Builder<static>|Role newModelQuery()
 * @method static Builder<static>|Role newQuery()
 * @method static Builder<static>|Role query()
 * @method static Builder<static>|Role whereAssignable()
 * @method static Builder<static>|Role whereSlug(...$slugs)
 * @mixin \Eloquent
 */
	class Role extends \Eloquent implements \Sentinel\Contracts\RoleContract {}
}

