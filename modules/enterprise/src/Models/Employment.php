<?php

declare(strict_types=1);

namespace Modules\Enterprise\Models;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Enterprise\Database\Factories\EmploymentFactory;

#[UseFactory(EmploymentFactory::class)]
class Employment extends Model
{
    /** @use HasFactory<EmploymentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'department_id',
        'contract_type_id',
        'employee_number',
        'position_title',
        'started_on',
        'ended_on',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** @return BelongsTo<ContractType, $this> */
    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    /** @param Builder<static> $query */
    #[Scope]
    protected function active(Builder $query, ?CarbonInterface $at = null): void
    {
        $date = ($at ?? now())->toDateString();

        $query
            ->where('started_on', '<=', $date)
            ->where(function (Builder $query) use ($date): void {
                $query->whereNull('ended_on')->orWhere('ended_on', '>=', $date);
            });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'started_on' => 'date',
            'ended_on' => 'date',
        ];
    }
}
