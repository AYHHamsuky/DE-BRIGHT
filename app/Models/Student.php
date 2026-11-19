<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory;

    public const STATUSES = ['active' => 'Active', 'graduated' => 'Graduated', 'suspended' => 'Suspended', 'withdrawn' => 'Withdrawn'];

    protected $fillable = [
        'user_id', 'reg_number', 'course_id', 'level', 'admission_session', 'phone', 'gender',
        'date_of_birth', 'state_of_origin', 'address', 'guardian_name', 'guardian_phone', 'status',
    ];

    protected $casts = ['date_of_birth' => 'date'];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Course, $this> */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** @return HasMany<Payment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term) {
            $query->where('reg_number', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhereHas('user', fn (Builder $user) => $user->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
        });
    }

    /**
     * Active fees that apply to this student: fees for every course plus fees for their own course.
     *
     * @return Collection<int, Fee>
     */
    public function applicableFees(): Collection
    {
        return Fee::query()
            ->where('is_active', true)
            ->where(fn (Builder $query) => $query->whereNull('course_id')->orWhere('course_id', $this->course_id))
            ->orderByDesc('session')
            ->orderBy('name')
            ->get();
    }

    /**
     * Each applicable fee with how much has been paid and how much is left.
     *
     * @return Collection<int, array{fee: Fee, paid: int, balance: int}>
     */
    public function feeStatement(): Collection
    {
        $paidByFee = $this->payments()
            ->where('status', PaymentStatus::Successful)
            ->whereNotNull('fee_id')
            ->selectRaw('fee_id, SUM(amount) as total')
            ->groupBy('fee_id')
            ->pluck('total', 'fee_id');

        return $this->applicableFees()->map(function (Fee $fee) use ($paidByFee) {
            $paid = (int) ($paidByFee[$fee->id] ?? 0);

            return ['fee' => $fee, 'paid' => $paid, 'balance' => max(0, $fee->amount - $paid)];
        });
    }

    public function balanceFor(Fee $fee): int
    {
        $paid = (int) $this->payments()
            ->where('status', PaymentStatus::Successful)
            ->where('fee_id', $fee->id)
            ->sum('amount');

        return max(0, $fee->amount - $paid);
    }

    public function feeApplies(Fee $fee): bool
    {
        return $fee->is_active && ($fee->course_id === null || $fee->course_id === $this->course_id);
    }
}
