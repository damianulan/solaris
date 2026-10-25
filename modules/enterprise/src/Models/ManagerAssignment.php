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
use Modules\Enterprise\Database\Factories\ManagerAssignmentFactory;

#[UseFactory(ManagerAssignmentFactory::class)]
class ManagerAssignment extends Model
{
    /** @use HasFactory<ManagerAssignmentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'manager_user_id',
        'employee_user_id',
        'started_on',
        'ended_on',
    ];

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<User, $this> */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_user_id');
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
