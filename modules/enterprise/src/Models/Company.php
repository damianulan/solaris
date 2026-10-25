<?php

declare(strict_types=1);

namespace Modules\Enterprise\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Enterprise\Database\Factories\CompanyFactory;

#[UseFactory(CompanyFactory::class)]
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'legal_name',
        'registration_number',
        'tax_identifier',
        'email',
        'phone',
        'website',
    ];

    /** @return HasMany<Department, $this> */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /** @return HasMany<ContractType, $this> */
    public function contractTypes(): HasMany
    {
        return $this->hasMany(ContractType::class);
    }

    /** @return HasMany<Employment, $this> */
    public function employments(): HasMany
    {
        return $this->hasMany(Employment::class);
    }

    /** @return HasMany<ManagerAssignment, $this> */
    public function managerAssignments(): HasMany
    {
        return $this->hasMany(ManagerAssignment::class);
    }
}
