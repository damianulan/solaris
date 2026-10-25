<?php

declare(strict_types=1);

namespace Modules\Enterprise\Concerns;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\Employment;
use Modules\Enterprise\Models\ManagerAssignment;
use Sentinel\Contracts\PermissionContract;
use Sentinel\Helpers\PermissionHelper;
use Sentinel\Helpers\SentinelHelper;
use Sentinel\Traits\HasRolesAndPermissions;

trait HasEnterpriseRelationships
{
    use HasRolesAndPermissions;

    /** @return HasMany<Employment, $this> */
    public function employments(): HasMany
    {
        return $this->hasMany(Employment::class);
    }

    /** @return HasMany<Employment, $this> */
    public function activeEmployments(): HasMany
    {
        $date = now()->toDateString();
        $relation = $this->employments();

        $relation
            ->where('started_on', '<=', $date)
            ->where(function (Builder $query) use ($date): void {
                $query->whereNull('ended_on')->orWhere('ended_on', '>=', $date);
            });

        return $relation;
    }

    /** @return HasMany<ManagerAssignment, $this> */
    public function managerAssignments(): HasMany
    {
        return $this->hasMany(ManagerAssignment::class, 'manager_user_id');
    }

    /** @return HasMany<ManagerAssignment, $this> */
    public function reportingAssignments(): HasMany
    {
        return $this->hasMany(ManagerAssignment::class, 'employee_user_id');
    }

    /** @return BelongsToMany<User, $this> */
    public function managedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'manager_assignments',
            'manager_user_id',
            'employee_user_id',
        )->withPivot(['company_id', 'started_on', 'ended_on', 'deleted_at'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    /** @return BelongsToMany<User, $this> */
    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'manager_assignments',
            'employee_user_id',
            'manager_user_id',
        )->withPivot(['company_id', 'started_on', 'ended_on', 'deleted_at'])
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    public function isEmployee(?CarbonInterface $at = null): bool
    {
        return $this->hasActiveEmploymentQuery($at)->exists();
    }

    public function isEmployedBy(Company $company, ?CarbonInterface $at = null): bool
    {
        return $this->employments()
            ->whereBelongsTo($company)
            ->where('started_on', '<=', ($at ?? now())->toDateString())
            ->where(function (Builder $query) use ($at): void {
                $date = ($at ?? now())->toDateString();
                $query->whereNull('ended_on')->orWhere('ended_on', '>=', $date);
            })
            ->exists();
    }

    public function manages(User $employee, ?CarbonInterface $at = null): bool
    {
        $date = ($at ?? now())->toDateString();

        return $this->managerAssignments()
            ->where('employee_user_id', $employee->getKey())
            ->where('started_on', '<=', $date)
            ->where(function (Builder $query) use ($date): void {
                $query->whereNull('ended_on')->orWhere('ended_on', '>=', $date);
            })
            ->exists();
    }

    public function canManage(
        User $employee,
        PermissionContract | \BackedEnum | string | int $permission,
        ?CarbonInterface $at = null,
    ): bool {
        return $this->manages($employee, $at)
            && $this->hasPermissionTo($permission, $employee);
    }

    public function hasSystemPermission(
        PermissionContract | \BackedEnum | string | int $permission,
    ): bool {
        $permissionModel = PermissionHelper::findOrFail($permission);
        $permissionSlug = $permissionModel->getAttribute('slug');

        if (! is_string($permissionSlug)) {
            return false;
        }

        $permissionCandidates = $this->permissionCandidates($permissionSlug);

        if ($this->permissions()->whereIn('slug', $permissionCandidates)->exists()) {
            return true;
        }

        $defaultContext = SentinelHelper::getDefaultContext();

        return $this->roles()
            ->wherePivot('context_type', $defaultContext::class)
            ->wherePivot('context_id', $defaultContext->getKey())
            ->whereHas('permissions', function (Builder $query) use ($permissionCandidates): void {
                $query->whereIn('slug', $permissionCandidates);
            })
            ->exists();
    }

    /** @return array<int, string> */
    private function permissionCandidates(string $permission): array
    {
        $candidates = [$permission];
        $segments = explode('.', $permission);

        while (count($segments) > 1) {
            array_pop($segments);
            $candidates[] = implode('.', $segments) . '.*';
        }

        $candidates[] = '*';

        return array_values(array_unique($candidates));
    }

    /** @return HasMany<Employment, $this> */
    private function hasActiveEmploymentQuery(?CarbonInterface $at = null): HasMany
    {
        $date = ($at ?? now())->toDateString();
        $relation = $this->employments();

        $relation
            ->where('started_on', '<=', $date)
            ->where(function (Builder $query) use ($date): void {
                $query->whereNull('ended_on')->orWhere('ended_on', '>=', $date);
            });

        return $relation;
    }
}
