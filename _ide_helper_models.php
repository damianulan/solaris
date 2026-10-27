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
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\Employment> $activeEmployments
 * @property-read int|null $active_employments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\Employment> $employments
 * @property-read int|null $employments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $managedUsers
 * @property-read int|null $managed_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\ManagerAssignment> $managerAssignments
 * @property-read int|null $manager_assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $managers
 * @property-read int|null $managers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Sentinel\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\ManagerAssignment> $reportingAssignments
 * @property-read int|null $reporting_assignments_count
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User withPermission(?mixed ...$permissions)
 * @method static \Nexus\Auth\Builders\UserBuilder<static>|User withRole(?mixed ...$roles)
 */
	class User extends \Eloquent {}
}

namespace Modules\Enterprise\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $registration_number
 * @property string|null $tax_identifier
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $website
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\ContractType> $contractTypes
 * @property-read int|null $contract_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\Department> $departments
 * @property-read int|null $departments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\Employment> $employments
 * @property-read int|null $employments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\ManagerAssignment> $managerAssignments
 * @property-read int|null $manager_assignments_count
 * @method static \Modules\Enterprise\Database\Factories\CompanyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereLegalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereTaxIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withoutTrashed()
 */
	class Company extends \Eloquent {}
}

namespace Modules\Enterprise\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\Enterprise\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\Employment> $employments
 * @property-read int|null $employments_count
 * @method static \Modules\Enterprise\Database\Factories\ContractTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType withoutTrashed()
 */
	class ContractType extends \Eloquent {}
}

namespace Modules\Enterprise\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int|null $parent_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Department> $children
 * @property-read int|null $children_count
 * @property-read \Modules\Enterprise\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Enterprise\Models\Employment> $employments
 * @property-read int|null $employments_count
 * @property-read Department|null $parent
 * @method static \Modules\Enterprise\Database\Factories\DepartmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department withoutTrashed()
 */
	class Department extends \Eloquent {}
}

namespace Modules\Enterprise\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property int|null $department_id
 * @property int $contract_type_id
 * @property string|null $employee_number
 * @property string|null $position_title
 * @property \Illuminate\Support\Carbon $started_on
 * @property \Illuminate\Support\Carbon|null $ended_on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\Enterprise\Models\Company|null $company
 * @property-read \Modules\Enterprise\Models\ContractType|null $contractType
 * @property-read \Modules\Enterprise\Models\Department|null $department
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment active(?\Carbon\CarbonInterface $at = null)
 * @method static \Modules\Enterprise\Database\Factories\EmploymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereContractTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereEndedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment wherePositionTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereStartedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employment withoutTrashed()
 */
	class Employment extends \Eloquent {}
}

namespace Modules\Enterprise\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int $manager_user_id
 * @property int $employee_user_id
 * @property \Illuminate\Support\Carbon $started_on
 * @property \Illuminate\Support\Carbon|null $ended_on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\Enterprise\Models\Company|null $company
 * @property-read \App\Models\User|null $employee
 * @property-read \App\Models\User|null $manager
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment active(?\Carbon\CarbonInterface $at = null)
 * @method static \Modules\Enterprise\Database\Factories\ManagerAssignmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereEmployeeUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereEndedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereManagerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereStartedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagerAssignment withoutTrashed()
 */
	class ManagerAssignment extends \Eloquent {}
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

namespace Nexus\Monolith\Settings\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Model $model
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceModel query()
 */
	class PreferenceModel extends \Eloquent {}
}

namespace Nexus\Monolith\Settings\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingModel query()
 */
	class SettingModel extends \Eloquent {}
}

namespace Sentinel\Models{
/**
 * @property int|string $id
 * @property string $slug
 * @property bool $assignable
 * @property-read int|null $roles_count
 * @method static Builder<static> whereAssignable()
 * @method static Builder<static> whereSlug(string ...$slugs)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Sentinel\Models\Role> $roles
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent implements \Sentinel\Contracts\PermissionContract {}
}

namespace Sentinel\Models{
/**
 * @property int|string $id
 * @property string $slug
 * @property bool $assignable
 * @property-read int|null $permissions_count
 * @method static Builder<static> whereAssignable()
 * @method static Builder<static> whereSlug(string ...$slugs)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Sentinel\Models\Permission> $permissions
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent implements \Sentinel\Contracts\RoleContract {}
}

